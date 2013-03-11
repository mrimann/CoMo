<?php
namespace Mrimann\CoMo\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Commits
 *
 * @Flow\Scope("singleton")
 */
class CommitRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * Returns the commits of a given month
	 *
	 * @param string the month identifier
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function getCommitsByMonth($month) {
		$query = $this->createQuery();
		$query->matching(
			$query->like('date', $month . '%')
		);

		return $query->execute();
	}
}
?>