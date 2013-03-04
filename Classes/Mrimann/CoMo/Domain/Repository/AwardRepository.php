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
}
?>