<?php
namespace Mrimann\CoMo\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Mrimann.CoMo package
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\AwardRepository
	 * @Flow\Inject
	 */
	var $awardRepository;

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\CommitRepository
	 * @Flow\Inject
	 */
	var $commitRepository;

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\RepositoryRepository
	 * @Flow\Inject
	 */
	var $repositoryRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign(
			'numberOfCommits',
			$this->commitRepository->getCommitsByMonth($this->getMonthIdentifierLastMonth())->count()
		);

		$this->view->assign(
			'numberOfActiveRepositories',
			$this->repositoryRepository->extractTheRepositoriesFromAStackOfCommits(
				$this->commitRepository->getCommitsByMonth($this->getMonthIdentifierLastMonth())
			)->count()
		);

		$this->view->assign(
			'numberOfMonitoredRepositories',
			$this->repositoryRepository->countByIsActive(TRUE)
		);

		$this->view->assign(
			'coderOfTheMonthAward',
			$this->awardRepository->findLatestAwards(1)->getFirst()
		);

		$this->view->assign(
			'currentTopicAwards',
			$this->awardRepository->findCurrentTopicAwards()
		);
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