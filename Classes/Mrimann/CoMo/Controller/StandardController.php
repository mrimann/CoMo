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
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign(
			'coderOfTheMonthAwards',
			$this->awardRepository->findLatestAwards(4)
		);

		$this->view->assign(
			'currentTopicAwards',
			$this->awardRepository->findCurrentTopicAwards()
		);
	}

}

?>