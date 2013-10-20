<?php
namespace Mrimann\CoMo\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for AggregatedDataPerUser
 *
 * @Flow\Scope("singleton")
 */
class AggregatedDataPerUserRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * Returns either an existing aggregate-bucket for a given user+month combination - or creates
	 * a new one to which the commits can be added later on.
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Commit $commit
	 * @return \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser|object
	 */
	public function findByCommitterAndMonth(\Mrimann\CoMo\Domain\Model\Commit $commit) {
		$this->initializeOurSettings();
		if ($this->settings['whoGetsCredits'] == 'committer') {
			$email = $commit->getCommitterEmail();
			$name = $commit->getCommitterName();
		} else {
			$email = $commit->getAuthorEmail();
			$name = $commit->getAuthorName();
		}

		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('month', $commit->getMonthIdentifier()),
				$query->equals('userEmail', $email)
			)
		);
		$query->setLimit(1);

		$result = $query->execute();

		if ($result->count()) {
			$result = $result->getFirst();
		} else {
			$result = new \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser();
			$result->setMonth(
				$commit->getMonthIdentifier()
			);
			$result->setUserEmail(
				$email
			);
			$result->setUserName(
				$name
			);
			$this->add($result);
			$this->persistenceManager->persistAll();
		}

		return $result;
	}

	/**
	 * Finds the 10 best ranked committer (most commits in the given month)
	 *
	 * @param string the month-identifier
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findBestRankedForMonth($month) {
		$query = $this->createQuery();
		$query->matching(
			$query->equals('month', $month)
		);
		$query->setLimit(10);
		$query->setOrderings(
			array(
				'commitCount' => 'DESC'
			)
		);

		return $query->execute();
	}

	/**
	 * Finds the 10 best ranked committer (most commits in the given month)
	 *
	 * @param string the month-identifier
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findBestRankedForTopicAndMonth($topic, $month) {
		$relevantColumn = 'commitCount' . ucfirst($topic);

		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('month', $month),
				$query->greaterThan($relevantColumn, 0)
			)
		);
		$query->setLimit(10);
		$query->setOrderings(
			array(
				$relevantColumn => 'DESC'
			)
		);

		return $query->execute();
	}

	/**
	 * Counts the number of different commiters for a given month.
	 *
	 * @param string the month identifier in the format YYYY-MM
	 * @return integer the number of committers
	 */
	public function findNumberOfCommittersPerMonth($month) {
		return $this->countByMonth($month);
	}

	/**
	 * Initialized our package's settings in $this->settings for further use
	 */
	protected function initializeOurSettings() {
		$this->settings = $this->configurationManager->getConfiguration(
			\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
			'Mrimann.CoMo'
		);
	}
}
?>