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
class DataAggregatorCommandController extends \TYPO3\Flow\Cli\CommandController {

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
	 * Processes the cached meta data and aggregates it to a usable format so they can be used easily
	 * for showing some results.
	 *
	 * @return void
	 */
	public function processCommitsCommand() {
		$unprocessedCommits = $this->commitRepository->findByIsAggregated(FALSE);

		if ($unprocessedCommits === NULL) {
			$this->outputLine('There are no commits to be processed.');
			return;
		}

		foreach ($unprocessedCommits as $commit) {
			$this->processSingleCommit($commit);
			$commit->setIsAggregated(TRUE);
			$this->commitRepository->update($commit);
		}

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