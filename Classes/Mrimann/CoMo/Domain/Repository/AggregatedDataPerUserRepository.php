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
				$query->equals('user', $commit->getCommitterEmail())
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
			$result->setUser(
				$commit->getCommitterEmail()
			);
			$this->add($result);
			$this->persistenceManager->persistAll();
		}

		return $result;
	}
}
?>