<?php
namespace Mrimann\CoMo\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * DataAggregatorCommand command controller for the Mrimann.CoMo package
 *
 * @Flow\Scope("singleton")
 */
class DataAggregatorCommandController extends BaseCommandController {

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\CommitRepository
	 * @Flow\Inject
	 */
	var $commitRepository;

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\AggregatedDataPerUserRepository
	 * @Flow\Inject
	 */
	var $aggregatedDataPerUserRepository;

	/**
	 * @var \TYPO3\Flow\Persistence\Doctrine\PersistenceManager
	 * @Flow\Inject
	 */
	var $persistenceManager;

	/**
	 * Processes the cached meta data and aggregates it to a usable format so they can be used easily
	 * for showing some results.
	 *
	 * @param boolean whether the script should avoid any output
	 * @return void
	 */
	public function processCommitsCommand($quiet = FALSE) {
		$this->quiet = $quiet;

		$unprocessedCommits = $this->commitRepository->findByIsAggregated(FALSE);

		if ($unprocessedCommits->count() == 0) {
			$this->outputLine('There are no commits to be processed.');
			return;
		}

		$numberOfProcessedCommits = 0;
		foreach ($unprocessedCommits as $commit) {
			$this->processSingleCommit($commit);
			$commit->setIsAggregated(TRUE);
			$this->commitRepository->update($commit);
			$this->persistenceManager->persistAll();
			$numberOfProcessedCommits++;
		}

		$this->outputLine('-> aggregated ' . $numberOfProcessedCommits . ' commits.');

	}

	/**
	 * Processes a single commit and adds it (the numbers) to the aggregated data
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Commit $commit
	 */
	protected function processSingleCommit(\Mrimann\CoMo\Domain\Model\Commit $commit) {
		$aggregatedDataBucket = $this->aggregatedDataPerUserRepository->findByCommitterAndMonth($commit);

		$aggregatedDataBucket->addCommit($commit);

		$this->aggregatedDataPerUserRepository->update($aggregatedDataBucket);
	}

}

?>