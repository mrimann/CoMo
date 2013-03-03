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
	 * Returns either an existing aggregate-bucket for a given user+month combination - or creates
	 * a new one to which the commits can be added later on.
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Commit $commit
	 * @return \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser|object
	 */
	public function findByCommitterAndMonth(\Mrimann\CoMo\Domain\Model\Commit $commit) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('month', $commit->getMonthIdentifier()),
				$query->equals('userEmail', $commit->getCommitterEmail())
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
				$commit->getCommitterEmail()
			);
			$result->setUserName(
				$commit->getCommitterName()
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

}
?>