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
	 * Extracts meta data from the commits of a repository
	 *
	 * @return void
	 */
	public function processRepositoriesCommand() {
		$repositories = $this->repositoryRepository->findAll();
		foreach ($repositories as $repository) {
			$this->outputLine('Going to process repository at %s', array($repository->getUrl()));
			$this->processSingleRepository($repository);
		}
	}

	/**
	 * @param \Mrimann\CoMo\Domain\Model\Repository $repository
	 */
	protected function processSingleRepository(\Mrimann\CoMo\Domain\Model\Repository $repository) {
		$workingDirectory = $this->getCachePath($repository);
		$this->outputLine('-> working directory is: ' . $workingDirectory);

		$this->prepareCachedClone($repository, $workingDirectory);
		$this->outputLine('-> cached clone is read to rumble...');

		$this->outputLine('-------------------');
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