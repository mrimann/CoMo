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
class ElectionCommandController extends BaseCommandController {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * The settings for our package
	 *
	 * @var array our settings
	 */
	protected $settings;

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
	 * Initialized our package's settings in $this->settings for further use
	 */
	protected function initializeOurSettings() {
		$this->settings = $this->configurationManager->getConfiguration(
			\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
			'Mrimann.CoMo'
		);
	}
	/**
	 * Runs the election for the last month.
	 *
	 * @param boolean whether the script should avoid any output
	 * @return void
	 */
	public function electLastMonthCommand($quiet = FALSE) {
		$this->initializeOurSettings();

		$this->quiet = $quiet;

		$monthIdentifier = $this->electomat->getMonthIdentifierLastMonth();

		$this->outputLine('Getting the awards for ' . $monthIdentifier);

		// elect the global committer of the month award
		if ($this->canAwardBeElected('committerOfTheMonth', $monthIdentifier) === TRUE) {
			$this->showTopRankingAndAnnouncement('committerOfTheMonth');
			$award = $this->createNewAward('committerOfTheMonth', $monthIdentifier, $this->topCommitters->getFirst());
			if ($this->settings['sendNotificationMailsForCoderOfTheMonth']) {
				$this->notifyCeremonyMasterOnNewAward($award);
				$this->outputLine('-> notifiying the ceremony master about that lucky moment...');
			} else {
				$this->outputLine('Mail notifications disabled, no mail sent to the ceremony master!');
			}
		}

		// elect the topic awards
		$topicAwardTypes = array(
			'feature',
			'bugfix',
			'task',
			'documentation',
			'test',
			'release'
		);
		foreach ($topicAwardTypes as $awardType) {
			unset($this->topCommitters);

			if ($this->canAwardBeElected($awardType, $monthIdentifier)) {
				$this->showTopRankingAndAnnouncement($awardType);

				// create the award
				$award = $this->createNewAward($awardType, $monthIdentifier, $this->topCommitters->getFirst());

				// notify the ceremony master
				if ($this->settings['sendNotificationMailsForTopicAwardWinner']) {
					$this->notifyCeremonyMasterOnNewTopicAward($award);
					$this->outputLine('-> notifiying the ceremony master about that lucky moment...');
				} else {
					$this->outputLine('Mail notifications disabled, no mail sent to ceremony master!');
				}
			}
		}
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
		$this->outputLine(
			'Checking the "%s" award for month %s:',
			array(
				$type,
				$monthIdentifier
			)
		);

		// first check if there is an award stored already
		if ($this->awardRepository->findByMonthAndType($monthIdentifier, $type)->count()) {
			$this->outputLine('This award has been given already.');
			return FALSE;
		}

		// check if we have data for this month at all
		if ($type == 'committerOfTheMonth') {
			$this->topCommitters = $this->electomat->getAwardsForMonth($monthIdentifier);
		} else {
			$this->topCommitters = $this->electomat->getTopicAwardsForMonth($type, $monthIdentifier);
		}

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
	 * @param string the type of the award
	 *
	 * @return void
	 */
	protected function showTopRankingAndAnnouncement($type) {
		foreach ($this->topCommitters as $topCommitter) {
			$this->outputLine(
				'%d commits from %s <%s>',
				array(
					$topCommitter->getCommitCountByType($type),
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
				$this->topCommitters->getFirst()->getCommitCountByType($type)
			)
		);
	}

	/**
	 * Creates an award of a given type
	 *
	 * @param string the type of the award
	 * @param string the month identifier
	 * @param \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser $winner
	 *
	 * @return \Mrimann\CoMo\Domain\Model\Award the new award
	 */
	protected function createNewAward($type, $monthIdentifier, \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser $winner) {
		$award = new \Mrimann\CoMo\Domain\Model\Award();
		$award->setUserEmail($winner->getUserEmail());
		$award->setUserName($winner->getUserName());
		$award->setType($type);
		$award->setMonth($monthIdentifier);
		$award->setCommitCount($winner->getCommitCountByType($type));
		$this->awardRepository->add($award);

		return $award;
	}

	/**
	 * Sends a mail to the ceremony master to notify him about a newly elected
	 * monthly winner in the master category "coder of the month".
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Award $award
	 * @return void
	 */
	protected function notifyCeremonyMasterOnNewAward(\Mrimann\CoMo\Domain\Model\Award $award) {
		$messageBody = 'We\'ve just elected the next winner:' . "\n\n" .
			'The award for the month ' . $award->getMonth() . ' was won by ' . $award->getUserName() . ' (' .
			$award->getUserEmail() . ') with ' . $award->getCommitCount() . ' commits.';

		$this->sendNotificationMail(
			$this->settings['ceremonyMasterEmail'],
			'Ceremony Master',
			'New Coder of the Month elected!',
			$messageBody
		);
	}

	/**
	 * Sends a mail to the ceremony master to notify him about a newly elected
	 * topic award.
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Award $award
	 * @return void
	 */
	protected function notifyCeremonyMasterOnNewTopicAward(\Mrimann\CoMo\Domain\Model\Award $award) {
		$messageBody = 'We\'ve just elected the next topic-award:' . "\n\n" .
			'The award in the category "' . $award->getType() . '" for the month ' .
			$award->getMonth() . ' was won by ' . $award->getUserName() . ' (' .
			$award->getUserEmail() . ') with ' . $award->getCommitCount() . ' commits.';

		$this->sendNotificationMail(
			$this->settings['ceremonyMasterEmail'],
			'Ceremony Master',
			'New topic award elected',
			$messageBody
		);
	}

	/**
	 * Helper function to instantiate SwiftMailer and effectively send the mail
	 * out to the recipient.
	 *
	 * @param string the recipient's e-mail address
	 * @param string the name of the recipient
	 * @param string the message's subject
	 * @param string the message's body
	 *
	 * @return void
	 */
	protected function sendNotificationMail($recipientEmail, $recipientName, $subject, $messageBody) {
		$mail = new \TYPO3\SwiftMailer\Message();
		$mail->setFrom(array($this->settings['senderEmail'] => $this->settings['senderName']))
			->setTo(array($recipientEmail => $recipientName))
			->setSubject($subject)
			->setBody($messageBody);
		$mail->send();
	}
}
?>