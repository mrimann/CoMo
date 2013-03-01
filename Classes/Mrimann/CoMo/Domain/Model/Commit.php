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
	 * The author
	 * @var string
	 */
	protected $author;

	/**
	 * The mail
	 * @var string
	 */
	protected $mail;

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
	}

	/**
	 * Get the Commit's author
	 *
	 * @return string The Commit's author
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Sets this Commit's author
	 *
	 * @param string $author The Commit's author
	 * @return void
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * Get the Commit's mail
	 *
	 * @return string The Commit's mail
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * Sets this Commit's mail
	 *
	 * @param string $mail The Commit's mail
	 * @return void
	 */
	public function setMail($mail) {
		$this->mail = $mail;
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