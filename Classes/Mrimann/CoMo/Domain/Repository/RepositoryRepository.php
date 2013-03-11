<?php
namespace Mrimann\CoMo\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Repositories
 *
 * @Flow\Scope("singleton")
 */
class RepositoryRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * Takes a pile of commits, then puts together all the repositories that are affected by
	 * at least one of the given commits.
	 *
	 * @param \TYPO3\Flow\Persistence\QueryResultInterface the pile of commits
	 * @return \Doctrine\Common\Collections\ArrayCollection the resulting stack of repositories
	 */
	public function extractTheRepositoriesFromAStackOfCommits(\TYPO3\Flow\Persistence\QueryResultInterface $commits){
		$result = new \Doctrine\Common\Collections\ArrayCollection();

		if ($commits->count()) {
			foreach ($commits as $commit) {
				if (!$result->contains($commit->getRepository())) {
					$result->add($commit->getRepository());
				}
			}
		}

		return $result;
	}
}
?>