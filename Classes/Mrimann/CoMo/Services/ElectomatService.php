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
	 * Returns the best ranked committers (overall commit count)
	 *
	 * @param string the month-identifier
	 */
	public function getAwardsForMonth($month) {
		return $this->aggregatedDataPerUserRepository->findBestRankedForMonth($month);
	}

	/**
	 * Returns the best ranked committers for a given topic and month
	 *
	 * @param string the topic
	 * @param string the month identifier
	 */
	public function getTopicAwardsForMonth($topic, $month) {
		return $this->aggregatedDataPerUserRepository->findBestRankedForTopicAndMonth($topic, $month);
	}

	/**
	 * Creates the identifier for last month in the format "YYYY-MM".
	 *
	 * @return string the month identifier
	 */
	public function getMonthIdentifierLastMonth() {
		$numberOfTheMonth = date('n') - 1;
		$date = mktime(1, 1, 1, $numberOfTheMonth, 1, date('Y'));

		return strftime('%Y-%m', $date);
	}
}
?>