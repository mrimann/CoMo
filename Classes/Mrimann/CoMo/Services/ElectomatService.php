<?php
namespace Mrimann\CoMo\Services;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * The service that elects the awards after checking some rules.
 */
class ElectomatService {

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\AggregatedDataPerUserRepository
	 * @Flow\Inject
	 */
	var $aggregatedDataPerUserRepository;

	/**
	 *
	 * @param string the month-identifier
	 */
	public function getAwardsForMonth($month) {
		return $this->aggregatedDataPerUserRepository->findBestRankedForMonth($month);

	}
}
?>