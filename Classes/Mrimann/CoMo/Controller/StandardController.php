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
	 * @var \Mrimann\CoMo\Domain\Repository\AggregatedDataPerUserRepository
	 * @Flow\Inject
	 */
	var $aggregatedDataPerUserRepository;

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
	 * @var \Mrimann\CoMo\Services\ElectomatService
	 * @Flow\Inject
	 */
	var $electomatService;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$monthIdentifier = $this->electomatService->getMonthIdentifierLastMonth();

		$this->view->assign(
			'numberOfCommits',
			$this->commitRepository->getCommitsByMonth($monthIdentifier)->count()
		);

		$this->view->assign(
			'numberOfActiveRepositories',
			$this->repositoryRepository->extractTheRepositoriesFromAStackOfCommits(
				$this->commitRepository->getCommitsByMonth($monthIdentifier)
			)->count()
		);

		$this->view->assign(
			'numberOfMonitoredRepositories',
			$this->repositoryRepository->countByIsActive(TRUE)
		);

		$this->view->assign(
			'numberOfCommitters',
			$this->aggregatedDataPerUserRepository->findNumberOfCommittersPerMonth($monthIdentifier)
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
}

?>