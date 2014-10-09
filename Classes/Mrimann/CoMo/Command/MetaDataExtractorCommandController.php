<?php
namespace Mrimann\CoMo\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * CommitMetaDataExtractorCommand command controller for the Mrimann.CoMo package
 *
 * @Flow\Scope("singleton")
 */
class MetaDataExtractorCommandController extends BaseCommandController {
	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	var $configurationManager;

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\RepositoryRepository
	 * @Flow\Inject
	 */
	var $repositoryRepository;

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\CommitRepository
	 * @Flow\Inject
	 */
	var $commitRepository;

	/**
	 * Extracts meta data from the commits of a repository
	 *
	 * @param boolean whether the script should avoid any output
	 * @return void
	 */
	public function processRepositoriesCommand($quiet = FALSE) {
		$this->quiet = $quiet;

		$repositories = $this->repositoryRepository->findByIsActive(TRUE);
		foreach ($repositories as $repository) {
			$this->outputLine('Going to process repository at %s', array($repository->getUrl()));
			$this->processSingleRepository($repository);
		}
	}

	/**
	 * @param \Mrimann\CoMo\Domain\Model\Repository $repository
	 */
	protected function processSingleRepository(\Mrimann\CoMo\Domain\Model\Repository $repository) {
		// prepare local cached clone for remote repositories if we're not on a local repo
		if ($repository->isLocalRepository() === FALSE) {
			$workingDirectory = $this->getCachePath($repository);
			$this->outputLine('-> working directory is: ' . $workingDirectory);

			$preparationResult = $this->prepareCachedClone($repository, $workingDirectory);
			if ($preparationResult === TRUE) {
				$this->outputLine('-> cached clone is ready to rumble...');
			} else {
				$this->outputLine('-> preparing local cache failed somehow, giving up on this repo.');
				return;
			}
		} else {
			$this->outputLine('-> OK, a local repository - just using that directory, no clone or pulling needed.');

			$workingDirectory = $repository->getUrl();
			$this->outputLine('-> working directory is: ' . $workingDirectory);
		}

		$lastProcessedHash = $this->extractCommits($repository);
		if ($lastProcessedHash != '') {
			$repository->setLastProcessedCommit($lastProcessedHash);
			$this->repositoryRepository->update($repository);
		}
		$this->outputLine('-> finished extracting the commits.');

		$this->outputLine('-------------------');
	}

	/**
	 * Extracts the commits of a repository, that are not yet extracted and put into the database.
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Repository $repository
	 * @return mixed
	 */
	protected function extractCommits(\Mrimann\CoMo\Domain\Model\Repository $repository) {
		// Fetch all commits since the last run (or full log if nothing done yet)
		unset($output);
		$lastProcessedCommit = $repository->getLastProcessedCommit();

		// use the --git-dir parameter for git log in case it's a local repository
		$gitDirectory = '';
		if ($repository->isLocalRepository()) {
			$gitDirectory = '--git-dir ' . substr(substr($repository->getUrl(),7), 0);
		}

		// check if there are commits at all (to avoid nasty CLI output in case we hit
		// an empty repository without any commits in it)
		$noCommitsYetOutput = array();
		$noCommitsYetExitCode = 0;
		@exec(
			'git ' . $gitDirectory . ' log -n 1 > /dev/null 2>&1',
			$noCommitsYetOutput,
			$noCommitsYetExitCode
		);
		if ($noCommitsYetExitCode > 0) {
			$this->outputLine('-> seen an empty repository without any commit at all, skipping!');
			return '';
		}


		$logRange = '';
		if ($lastProcessedCommit != '') {
			$this->outputLine('-> there are commits already, extracting since ' . substr($lastProcessedCommit, 0, 8));
			$logRange = $lastProcessedCommit . '..HEAD';
		} else {
			// check if there's a limit of days to fetch the history from the git log
			if (isset($this->settings['maxDaysToFetchFromGitLogHistory'])
				&& $this->settings['maxDaysToFetchFromGitLogHistory'] > 0) {
				$this->outputLine(
					'-> Limit of max %d days to fetch is in effect, nothing older than that will be extracted',
					array(
						$this->settings['maxDaysToFetchFromGitLogHistory']
					)
				);
				$oldestDate = new \DateTime('now -' . $this->settings['maxDaysToFetchFromGitLogHistory'] . ' days');
				$logRange = '--since ' . $oldestDate->format('Y-m-d');
			}
		}
		exec('git ' . $gitDirectory . ' log ' . $logRange . ' --reverse --pretty="%H__mrX__%ai__mrX__%aE__mrX__%aN__mrX__%cE__mrX__%cN__mrX__%s"', $output);

		// check if there are new commits at all
		if (count($output) == 0) {
			$this->outputLine('-> no commits found to extract');
			return '';
		}

		// Loop over the single commits and store their data in the database
		foreach ($output as $line) {
			$lineParts = explode('__mrX__', $line);
			$this->outputLine('-> extracting commit ' . substr($lineParts[0], 0, 8));

			$commit = new \Mrimann\CoMo\Domain\Model\Commit();
			$commit->setHash($lineParts[0]);
			$date = new \DateTime($lineParts[1]);
			$commit->setDate($date);
			$commit->setAuthorEmail($lineParts[2]);
			$commit->setAuthorName($lineParts[3]);
			$commit->setCommitterEmail($lineParts[4]);
			$commit->setCommitterName($lineParts[5]);
			$commit->setCommitLine($lineParts[6]);
			$commit->setRepository($repository);

			$this->commitRepository->add($commit);
			$lastHash = $lineParts[0];
		}

		return $lastHash;
	}

	/**
	 * Checks if the given repository has already been cloned to it's temporary cache directory.
	 * If it is, the clone is updated by pulling in all changes from the source URL, otherwise
	 * the source repository is cloned to the directory.
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Repository $repository
	 * @param $workingDirectory
	 *
	 * @return boolean returns TRUE if preparation was successful, FALSE otherwise
	 */
	protected function prepareCachedClone(\Mrimann\CoMo\Domain\Model\Repository $repository, $workingDirectory) {
		// If working directory does not exist, create it by cloning repository into it
		// and then change into that directory
		if (!is_dir($workingDirectory . '/.git')) {
			$this->outputLine($workingDirectory);
			chdir($workingDirectory);
			$this->outputLine('Created directory, going to clone now...');
			$cloneResult = '';
			exec('git clone ' . $repository->getUrl() . ' .', $foo, $cloneResult);
			if ($cloneResult > 0) {
				$this->outputLine('Oops, something went wrong while cloning...');
				$result = FALSE;
			} else {
				$this->outputLine('Finished cloning from ' . $repository->getUrl());
				$result = TRUE;
			}
		} else {
			chdir($workingDirectory);
			// If there's a clone alredy, just pull the latest changes from the origin
			$this->outputLine('going to pull changes from remote repo...');

			$pullResult = '';
			exec ('git pull', $unusedOutput, $pullResult);
			if ($pullResult > 0) {
				$this->outputLine('Oops, something went wrong while pulling the repository...');
				$result = FALSE;
			} else {
				$this->outputLine('finished pulling changes.');
				$result = TRUE;
			}
		}

		return $result;
	}

	/**
	 * @param \Mrimann\CoMo\Domain\Model\Repository $repository
	 * @return string path to the cache directory for this repository
	 */
	protected function getCachePath(\Mrimann\CoMo\Domain\Model\Repository $repository) {
		$applicationContext = new \TYPO3\Flow\Core\ApplicationContext('Development');
		$environment = new \TYPO3\Flow\Utility\Environment($applicationContext);

		// Fiddle out the path to the temporary directory, depending on the Flow-Context
		$this->configurationManager->injectEnvironment($environment);
		$this->settings = $this->configurationManager->getConfiguration('Settings', 'Mrimann.CoMo');
		$environment->setTemporaryDirectoryBase($this->settings['cacheBasePath']);
		$workingBaseDirectory = $environment->getPathToTemporaryDirectory();

		// Create the working directory for this repository
		$workingDirectory = $workingBaseDirectory . 'Mrimann.CoMo/' . $repository->getIdentity();
		if (!is_dir($workingDirectory)) {
			mkdir($workingDirectory, 0777, TRUE);
		}

		return $workingDirectory;
	}
}

?>