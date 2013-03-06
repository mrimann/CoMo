<?php
namespace Mrimann\CoMo\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Awards
 *
 * @Flow\Scope("singleton")
 */
class AwardRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * Finds the award of a given type for a specific month.
	 *
	 * @param string the month identifier
	 * @param type the type of the award
	 *
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findByMonthAndType($monthIdentifier, $type) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('month', $monthIdentifier),
				$query->equals('type', $type)
			)
		);

		return $query->execute();
	}

	/**
	 * Finds the latest n committerOftheMonth awards, ordered by
	 * the month (newest first)
	 *
	 * @param integer the limit of awards to select
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findLatestAwards($limit = 3) {
		$query = $this->createQuery();
		$query->matching(
			$query->equals('type', 'committerOfTheMonth')
		);
		$query->setLimit($limit);
		$query->setOrderings(
			array(
				'month' => 'DESC'
			)
		);

		return $query->execute();
	}

	/**
	 * Finds the topic awards (everything that is not the basic committer of the month award)
	 * for the current month.
	 *
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findCurrentTopicAwards() {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('month', $this->getMonthIdentifierLastMonth()),
				$query->logicalNot(
					$query->equals('type', 'committerOfTheMonth')
				)
			)
		);

		return $query->execute();
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
}
?>