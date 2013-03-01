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
		$this->outputLine('Working directory is: ' . $workingDirectory);

		
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