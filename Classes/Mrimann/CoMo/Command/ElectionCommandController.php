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
	 * Runs the election for the last month.
	 *
	 * @return void
	 */
	public function electLastMonthCommand() {
		// build last month's month-identifier
		$date = mktime(1, 1, 1, date('m') - 1, 1, date('Y'));
		$month = new \DateTime('@' . $date);
		$monthIdentifier = $month->format('Y-m');

		$this->outputLine('Getting the awards for ' . $monthIdentifier);

		// first check if there is an award stored already
		if ($this->awardRepository->findByMonthAndType($monthIdentifier, 'committerOfTheMonth')->count()) {
			$this->outputLine('This award has been given already.');
			return;
		}

		$hits = $this->electomat->getAwardsForMonth($monthIdentifier);

		if ($hits->count() === 0) {
			$this->outputLine('Nothing found, sorry...');
			return;
		}

		foreach ($hits as $hit) {
			$this->outputLine(
				'%d commits from %s <%s>',
				array(
					$hit->getCommitCount(),
					$hit->getUserName(),
					$hit->getUserEmail()
				)
			);
		}

		$this->outputLine(
			'Happy to announce that the winner is: %s (%s) with %d commits!',
			array(
				$hits->getFirst()->getUserName(),
				$hits->getFirst()->getUserEmail(),
				$hits->getFirst()->getCommitCount()
			)
		);

		$award = new \Mrimann\CoMo\Domain\Model\Award();
		$award->setUserEmail($hits->getFirst()->getUserEmail());
		$award->setUserName($hits->getFirst()->getUserName());
		$award->setType('committerOfTheMonth');
		$award->setMonth($monthIdentifier);
		$this->awardRepository->add($award);
	}
}
?>