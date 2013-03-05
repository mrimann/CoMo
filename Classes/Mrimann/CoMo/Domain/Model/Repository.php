<?php
namespace Mrimann\CoMo\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Repository
 *
 * @Flow\Entity
 */
class Repository {

	/**
	 * The title
	 * @var string
	 */
	protected $title;

	/**
	 * The url
	 * @var string
	 */
	protected $url;

	/**
	 * The last processed Commit
	 * @var string
	 */
	protected $lastProcessedCommit;

	/**
	 * The is active
	 * @var boolean
	 */
	protected $isActive;

	public function __construct() {
		$this->setIsActive(FALSE);
		$this->setLastProcessedCommit('');
	}

	/**
	 * Returns the identity of this repository
	 *
	 * @return string the UUID of this repository
	 */
	public function getIdentity() {
		return $this->Persistence_Object_Identifier;
	}

	/**
	 * Get the Repository's title
	 *
	 * @return string The Repository's title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets this Repository's title
	 *
	 * @param string $title The Repository's title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get the Repository's url
	 *
	 * @return string The Repository's url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Sets this Repository's url
	 *
	 * @param string $url The Repository's url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @param string $lastProcessedCommit
	 */
	public function setLastProcessedCommit($lastProcessedCommit) {
		$this->lastProcessedCommit = $lastProcessedCommit;
	}

	/**
	 * @return string
	 */
	public function getLastProcessedCommit() {
		return $this->lastProcessedCommit;
	}

	/**
	 * Get the Repository's is active
	 *
	 * @return boolean The Repository's is active
	 */
	public function getIsActive() {
		return $this->isActive;
	}

	/**
	 * Sets this Repository's is active
	 *
	 * @param boolean $isActive The Repository's is active
	 * @return void
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}

	/**
	 * Checks if the repo is locally accessible (e.g. on the same server or on a
	 * mounted remote filesystem).
	 *
	 * @return boolean true if the URL of the repo is locally, false otherwise
	 */
	public function isLocalRepository() {
		if (substr($this->getUrl(), 0, 7) == 'file://') {
			return true;
		}

		return false;
	}

}
?>