<?php
namespace Mrimann\CoMo\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Aggregated data per user
 *
 * @Flow\Entity
 */
class AggregatedDataPerUser {

	/**
	 * The user email
	 * @var string
	 */
	protected $userEmail;

	/**
	 * The user name
	 * @var string
	 */
	protected $userName;

	/**
	 * The month
	 * @var string
	 */
	protected $month;

	/**
	 * The commit count
	 * @var integer
	 */
	protected $commitCount;

	/**
	 * The commit count bugfix
	 * @var integer
	 */
	protected $commitCountBugfix;

	/**
	 * The commit count feature
	 * @var integer
	 */
	protected $commitCountFeature;

	/**
	 * The commit count documentation
	 * @var integer
	 */
	protected $commitCountDocumentation;

	/**
	 * The commit count task
	 * @var integer
	 */
	protected $commitCountTask;

	/**
	 * The commit count test
	 * @var integer
	 */
	protected $commitCountTest;

	/**
	 * The commit count release
	 * @var integer
	 */
	protected $commitCountRelease;

	/**
	 * The commit count release
	 * @var integer
	 */
	protected $commitCountUnknown;


	public function __construct() {
		$this->commitCount = 0;
		$this->commitCountBugfix = 0;
		$this->commitCountDocumentation = 0;
		$this->commitCountFeature = 0;
		$this->commitCountRelease = 0;
		$this->commitCountTask = 0;
		$this->commitCountTest = 0;
		$this->commitCountUnknown = 0;
	}

	/**
	 * Get the Aggregated data per user's user
	 *
	 * @return string The Aggregated data per user's user
	 */
	public function getUserEmail() {
		return $this->userEmail;
	}

	/**
	 * Sets this Aggregated data per user's user
	 *
	 * @param string $user The Aggregated data per user's user
	 * @return void
	 */
	public function setUserEmail($user) {
		$this->userEmail = $user;
	}

	/**
	 * @param string $userName
	 */
	public function setUserName($userName) {
		$this->userName = $userName;
	}

	/**
	 * @return string
	 */
	public function getUserName() {
		return $this->userName;
	}

	/**
	 * Get the Aggregated data per user's month
	 *
	 * @return string the date identifier
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 * Sets this Aggregated data per user's month
	 *
	 * @param string the month identifier
	 * @return void
	 */
	public function setMonth($month) {
		$this->month = $month;
	}

	/**
	 * Get the Aggregated data per user's commit count
	 *
	 * @return integer The Aggregated data per user's commit count
	 */
	public function getCommitCount() {
		return (int)$this->commitCount;
	}

	/**
	 * Sets this Aggregated data per user's commit count
	 *
	 * @param integer $commitCount The Aggregated data per user's commit count
	 * @return void
	 */
	public function setCommitCount($commitCount) {
		$this->commitCount = $commitCount;
	}

	/**
	 * Get the Aggregated data per user's commit count bugfix
	 *
	 * @return integer The Aggregated data per user's commit count bugfix
	 */
	public function getCommitCountBugfix() {
		return (int)$this->commitCountBugfix;
	}

	/**
	 * Sets this Aggregated data per user's commit count bugfix
	 *
	 * @param integer $commitCountBugfix The Aggregated data per user's commit count bugfix
	 * @return void
	 */
	public function setCommitCountBugfix($commitCountBugfix) {
		$this->commitCountBugfix = $commitCountBugfix;
	}

	/**
	 * Get the Aggregated data per user's commit count feature
	 *
	 * @return integer The Aggregated data per user's commit count feature
	 */
	public function getCommitCountFeature() {
		return (int)$this->commitCountFeature;
	}

	/**
	 * Sets this Aggregated data per user's commit count feature
	 *
	 * @param integer $commitCountFeature The Aggregated data per user's commit count feature
	 * @return void
	 */
	public function setCommitCountFeature($commitCountFeature) {
		$this->commitCountFeature = $commitCountFeature;
	}

	/**
	 * Get the Aggregated data per user's commit count documentation
	 *
	 * @return integer The Aggregated data per user's commit count documentation
	 */
	public function getCommitCountDocumentation() {
		return (int)$this->commitCountDocumentation;
	}

	/**
	 * Sets this Aggregated data per user's commit count documentation
	 *
	 * @param integer $commitCountDocumentation The Aggregated data per user's commit count documentation
	 * @return void
	 */
	public function setCommitCountDocumentation($commitCountDocumentation) {
		$this->commitCountDocumentation = $commitCountDocumentation;
	}

	/**
	 * Get the Aggregated data per user's commit count task
	 *
	 * @return integer The Aggregated data per user's commit count task
	 */
	public function getCommitCountTask() {
		return (int)$this->commitCountTask;
	}

	/**
	 * Sets this Aggregated data per user's commit count task
	 *
	 * @param integer $commitCountTask The Aggregated data per user's commit count task
	 * @return void
	 */
	public function setCommitCountTask($commitCountTask) {
		$this->commitCountTask = $commitCountTask;
	}

	/**
	 * Get the Aggregated data per user's commit count test
	 *
	 * @return integer The Aggregated data per user's commit count test
	 */
	public function getCommitCountTest() {
		return (int)$this->commitCountTest;
	}

	/**
	 * Sets this Aggregated data per user's commit count test
	 *
	 * @param integer $commitCountTest The Aggregated data per user's commit count test
	 * @return void
	 */
	public function setCommitCountTest($commitCountTest) {
		$this->commitCountTest = $commitCountTest;
	}

	/**
	 * Get the Aggregated data per user's commit count release
	 *
	 * @return integer The Aggregated data per user's commit count release
	 */
	public function getCommitCountRelease() {
		return (int)$this->commitCountRelease;
	}

	/**
	 * Sets this Aggregated data per user's commit count release
	 *
	 * @param integer $commitCountRelease The Aggregated data per user's commit count release
	 * @return void
	 */
	public function setCommitCountRelease($commitCountRelease) {
		$this->commitCountRelease = $commitCountRelease;
	}

	/**
	 * @param int $commitCountUnknown
	 */
	public function setCommitCountUnknown($commitCountUnknown) {
		$this->commitCountUnknown = $commitCountUnknown;
	}

	/**
	 * @return int
	 */
	public function getCommitCountUnknown() {
		return $this->commitCountUnknown;
	}

	/**
	 * Returns the commit count for a given type of commits
	 *
	 * @param string the type, optional (returns the overall count if not given)
	 * @return int the commit count
	 */
	public function getCommitCountByType($type = '') {
		if ($type == '' || $type == 'committerOfTheMonth') {
			$result = $this->getCommitCount();
		}

		switch ($type) {
			case 'bugfix':
				$result = $this->getCommitCountBugfix();
				break;
			case 'feature':
				$result = $this->getCommitCountFeature();
				break;
			case 'test':
				$result = $this->getCommitCountTest();
				break;
			case 'documentation':
				$result = $this->getCommitCountDocumentation();
				break;
			case 'release':
				$result = $this->getCommitCountRelease();
				break;
			case 'task':
				$result = $this->getCommitCountTask();
				break;
			case 'unknown':
				$result = $this->getCommitCountUnknown();
				break;
		}

		return $result;
	}

	/**
	 * Adds a given commit (without further checking). Adding in this case means that some counters
	 * in this object are raised - depending on some properties of the commit.
	 *
	 * @param Commit $commit
	 * @return void
	 */
	public function addCommit(\Mrimann\CoMo\Domain\Model\Commit $commit){
		// Update the generic commit counter
		$this->commitCount++;

		// Update the topic commit counter, depending on the commit class of the commit
		switch ($commit->getCommitClass()) {
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_FEATURE:
				$this->commitCountFeature++;
				break;
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_RELEASE:
				$this->commitCountRelease++;
				break;
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_DOCUMENTATION:
				$this->commitCountDocumentation++;
				break;
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_TASK:
				$this->commitCountTask++;
				break;
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_BUGFIX:
				$this->commitCountBugfix++;
				break;
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_TEST:
				$this->commitCountTest++;
				break;
			case \Mrimann\CoMo\Domain\Model\Commit::CLASS_UNKNOWN:
				// fallthrough intended!
			default:
				$this->commitCountUnknown++;
				break;
		}
	}

}
?>