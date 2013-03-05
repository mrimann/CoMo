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
class MetaDataExtractorCommandController extends \TYPO3\Flow\Cli\CommandController {
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
	 * @return void
	 */
	public function processRepositoriesCommand() {
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
		// prepare local cached clone for remote repositories
		$workingDirectory = $this->getCachePath($repository);
		$this->outputLine('-> working directory is: ' . $workingDirectory);

		$this->prepareCachedClone($repository, $workingDirectory);
		$this->outputLine('-> cached clone is ready to rumble...');

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
			$gitDirectory = '--git-dir ' . substr(substr($repository->getUrl(),7), 0, -4) . '/.git';
		}

		$logRange = '';
		if ($lastProcessedCommit != '') {
			$this->outputLine('-> there are commits already, extracting since ' . substr($lastProcessedCommit, 0, 8));
			$logRange = $lastProcessedCommit . '..HEAD';
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
	 */
	protected function prepareCachedClone(\Mrimann\CoMo\Domain\Model\Repository $repository, $workingDirectory) {
		if ($repository->isLocalRepository()) {
			$this->outputLine('-> OK, a local repository - just changing to that dir, no clone or pulling needed.');
		} else {
			// If working directory does not exist, create it by cloning repository into it
			// and then change into that directory
			if (!is_dir($workingDirectory . '/.git')) {
				$this->outputLine($workingDirectory);
				chdir($workingDirectory);
				$this->outputLine('Created directory, going to clone now...');
				exec('git clone ' . $repository->getUrl() . ' .');
				$this->outputLine('Finished cloning from ' . $repository->getUrl());
			} else {
				chdir($workingDirectory);
				// If there's a clone alredy, just pull the latest changes from the origin
				$this->outputLine('going to pull changes from remote repo...');
				exec ('git pull');
				$this->outputLine('finished pulling changes.');
			}
		}

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