<?php
namespace Mrimann\CoMo\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Award
 *
 * @Flow\Entity
 */
class Award {

	/**
	 * The type
	 * @var string
	 */
	protected $type;

	/**
	 * The month
	 * @var string
	 */
	protected $month;

	/**
	 * The user name
	 * @var string
	 */
	protected $userName;

	/**
	 * The user email
	 * @var string
	 */
	protected $userEmail;


	/**
	 * Get the Award's type
	 *
	 * @return string The Award's type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets this Award's type
	 *
	 * @param string $type The Award's type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get the Award's month
	 *
	 * @return string The Award's month
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 * Sets this Award's month
	 *
	 * @param string $month The Award's month
	 * @return void
	 */
	public function setMonth($month) {
		$this->month = $month;
	}

	/**
	 * Get the Award's user name
	 *
	 * @return string The Award's user name
	 */
	public function getUserName() {
		return $this->userName;
	}

	/**
	 * Sets this Award's user name
	 *
	 * @param string $userName The Award's user name
	 * @return void
	 */
	public function setUserName($userName) {
		$this->userName = $userName;
	}

	/**
	 * Get the Award's user email
	 *
	 * @return string The Award's user email
	 */
	public function getUserEmail() {
		return $this->userEmail;
	}

	/**
	 * Sets this Award's user email
	 *
	 * @param string $userEmail The Award's user email
	 * @return void
	 */
	public function setUserEmail($userEmail) {
		$this->userEmail = $userEmail;
	}

}
?>