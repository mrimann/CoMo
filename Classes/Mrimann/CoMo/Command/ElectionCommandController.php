<?php
namespace Mrimann\CoMo\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Election command controller for the Mrimann.CoMo package
 *
 * @Flow\Scope("singleton")
 */
class ElectionCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Mrimann\CoMo\Services\ElectomatService
	 * @Flow\Inject
	 */
	var $electomat;

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\AwardRepository
	 * @Flow\Inject
	 */
	var $awardRepository;

	/**
	 * The best committers for an award that is being elected
	 *
	 * @var \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser
	 */
	var $topCommitters;

	/**
	 * Runs the election for the last month.
	 *
	 * @return void
	 */
	public function electLastMonthCommand() {
		$monthIdentifier = $this->getMonthIdentifierLastMonth();

		$this->outputLine('Getting the awards for ' . $monthIdentifier);

		if ($this->canAwardBeElected('committerOfTheMonth', $monthIdentifier) === TRUE) {
			$this->showTopRankingAndAnnouncement();
			$this->createNewAward('committerOfTheMonth', $monthIdentifier, $this->topCommitters->getFirst());
		} else {
			return;
		}

	}

	/**
	 * Creates the identifier for last month in the format "YYYY-MM".
	 *
	 * TODO: Refactor this one to be in the electomatService if possible
	 * @return string the month identifier
	 */
	protected function getMonthIdentifierLastMonth() {
		$date = mktime(1, 1, 1, date('m') - 1, 1, date('Y'));
		$month = new \DateTime('@' . $date);

		return $month->format('Y-m');
	}

	/**
	 * Checks if a given type of award for a specific month can be elected right now. This
	 * decision bases on two facts:
	 * - check if the award for this month is already given
	 * - check if we have data for this month to vote on
	 *
	 * @param string the type of the award
	 * @param string the month identifier
	 * @return boolean true if the award can be elected, false otherwise
	 */
	protected function canAwardBeElected($type, $monthIdentifier) {
		// first check if there is an award stored already
		if ($this->awardRepository->findByMonthAndType($monthIdentifier, $type)->count()) {
			$this->outputLine('This award has been given already.');
			return FALSE;
		}

		// check if we have data for this month at all
		$this->topCommitters = $this->electomat->getAwardsForMonth($monthIdentifier);

		if ($this->topCommitters->count() === 0) {
			$this->outputLine('Nothing found to elect on, sorry...');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Shows the top committers for an award and also announces the very first one as the winner.
	 *
	 * This is solely for the CLI output - does no data manipulation.
	 *
	 * @return void
	 */
	protected function showTopRankingAndAnnouncement() {
		foreach ($this->topCommitters as $topCommitter) {
			$this->outputLine(
				'%d commits from %s <%s>',
				array(
					$topCommitter->getCommitCount(),
					$topCommitter->getUserName(),
					$topCommitter->getUserEmail()
				)
			);
		}

		$this->outputLine(
			'Happy to announce that the winner is: %s (%s) with %d commits!',
			array(
				$this->topCommitters->getFirst()->getUserName(),
				$this->topCommitters->getFirst()->getUserEmail(),
				$this->topCommitters->getFirst()->getCommitCount()
			)
		);
	}

	/**
	 * Creates an award of a given type
	 *
	 * @param string the type of the award
	 * @param string the month identifier
	 * @param \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser $winner
	 */
	protected function createNewAward($type, $monthIdentifier, \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser $winner) {
		$award = new \Mrimann\CoMo\Domain\Model\Award();
		$award->setUserEmail($winner->getUserEmail());
		$award->setUserName($winner->getUserName());
		$award->setType($type);
		$award->setMonth($monthIdentifier);
		$this->awardRepository->add($award);
	}
}
?>