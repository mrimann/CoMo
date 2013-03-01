<?php
namespace Mrimann\CoMo\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Commit
 *
 * @Flow\Entity
 */
class Commit {

	const CLASS_UNKNOWN = 'unknown';
	const CLASS_FEATURE = 'feature';
	const CLASS_BUGFIX = 'bugfix';
	const CLASS_TASK = 'task';
	const CLASS_RELEASE = 'release';
	const CLASS_TEST = 'test';
	const CLASS_DOCUMENTATION = 'documentation';

	/**
	 * The repository
	 * @var \Mrimann\CoMo\Domain\Model\Repository
	 * @ORM\ManyToOne
	 */
	protected $repository;

	/**
	 * The hash
	 * @var string
	 */
	protected $hash;

	/**
	 * The commit line
	 * @var string
	 */
	protected $commitLine;

	/**
	 * The author name
	 * @var string
	 */
	protected $authorName;

	/**
	 * The author email
	 * @var string
	 */
	protected $authorEmail;

	/**
	 * The committer name
	 * @var string
	 */
	protected $committerName;

	/**
	 * The committer email
	 * @var string
	 */
	protected $committerEmail;

	/**
	 * The date
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * The commit class
	 * @var string
	 */
	protected $commitClass;

	/**
	 * The is aggregated
	 * @var boolean
	 */
	protected $isAggregated;

	public function __construct() {
		$this->isAggregated = FALSE;
	}

	/**
	 * Get the Commit's repository
	 *
	 * @return \Mrimann\CoMo\Domain\Model\Repository The Commit's repository
	 */
	public function getRepository() {
		return $this->repository;
	}

	/**
	 * Sets this Commit's repository
	 *
	 * @param \Mrimann\CoMo\Domain\Model\Repository $repository The Commit's repository
	 * @return void
	 */
	public function setRepository($repository) {
		$this->repository = $repository;
	}

	/**
	 * Get the Commit's hash
	 *
	 * @return string The Commit's hash
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Sets this Commit's hash
	 *
	 * @param string $hash The Commit's hash
	 * @return void
	 */
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	 * Get the Commit's commit line
	 *
	 * @return string The Commit's commit line
	 */
	public function getCommitLine() {
		return $this->commitLine;
	}

	/**
	 * Sets this Commit's commit line
	 *
	 * @param string $commitLine The Commit's commit line
	 * @return void
	 */
	public function setCommitLine($commitLine) {
		$this->commitLine = $commitLine;

		$this->setCommitClassFromCommitLine();
	}

	/**
	 * Get the Commit's author
	 *
	 * @return string The Commit's author
	 */
	public function getAuthorName() {
		return $this->authorName;
	}

	/**
	 * Sets this Commit's author
	 *
	 * @param string $author The Commit's author
	 * @return void
	 */
	public function setAuthorName($author) {
		$this->authorName = $author;
	}

	/**
	 * @param string $authorEmail
	 */
	public function setAuthorEmail($authorEmail) {
		$this->authorEmail = $authorEmail;
	}

	/**
	 * @return string
	 */
	public function getAuthorEmail() {
		return $this->authorEmail;
	}

	/**
	 * @param string $committerEmail
	 */
	public function setCommitterEmail($committerEmail) {
		$this->committerEmail = $committerEmail;
	}

	/**
	 * @return string
	 */
	public function getCommitterEmail() {
		return $this->committerEmail;
	}

	/**
	 * @param string $committerName
	 */
	public function setCommitterName($committerName) {
		$this->committerName = $committerName;
	}

	/**
	 * @return string
	 */
	public function getCommitterName() {
		return $this->committerName;
	}


	/**
	 * Get the Commit's date
	 *
	 * @return \DateTime The Commit's date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Sets this Commit's date
	 *
	 * @param \DateTime $date The Commit's date
	 * @return void
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * Returns the month identifier in the format "YYYY-MM" of the commit's date
	 *
	 * @return string the month identifier
	 */
	public function getMonthIdentifier() {
		return $this->getDate()->format('Y-m');
	}

	/**
	 * Get the Commit's commit class
	 *
	 * @return string The Commit's commit class
	 */
	public function getCommitClass() {
		return $this->commitClass;
	}

	/**
	 * Sets this Commit's commit class
	 *
	 * @param string $commitClass The Commit's commit class
	 * @return void
	 */
	public function setCommitClass($commitClass) {
		$this->commitClass = $commitClass;
	}

	protected function setCommitClassFromCommitLine() {
		if (preg_match('/^(\[)(.*)(\])(.*)/', $this->getCommitLine()) === 0) {
			$this->setCommitClass(self::CLASS_UNKNOWN);
		} else {
			// fiddle out the keyword within the brackets then
			preg_match_all(
				'/^(\[)(.*)(\])(.*)/',
				$this->getCommitLine(),
				$parts
			);

			switch (strtolower($parts[2][0])) {
				case 'task':
					$class = self::CLASS_TASK;
					break;
				case 'bugfix':
					$class = self::CLASS_BUGFIX;
					break;
				case 'feature':
					$class = self::CLASS_FEATURE;
					break;
				case 'release':
					$class = self::CLASS_RELEASE;
					break;
				case 'documentation':
					$class = self::CLASS_DOCUMENTATION;
					break;
				case 'test':
					$class = self::CLASS_TEST;
					break;
				default:
					$class = self::CLASS_UNKNOWN;
			}
			$this->setCommitClass($class);
		}
	}

	/**
	 * Get the Commit's is aggregated
	 *
	 * @return boolean The Commit's is aggregated
	 */
	public function getIsAggregated() {
		return $this->isAggregated;
	}

	/**
	 * Sets this Commit's is aggregated
	 *
	 * @param boolean $isAggregated The Commit's is aggregated
	 * @return void
	 */
	public function setIsAggregated($isAggregated) {
		$this->isAggregated = $isAggregated;
	}

}
?>