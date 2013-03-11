<?php
namespace Mrimann\CoMo\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

/**
 * Testcase for Award
 */
class AwardTest extends \TYPO3\Flow\Tests\UnitTestCase {
	/**
	 * @var \Mrimann\CoMo\Domain\Model\Award
	 */
	var $fixture;

	public function setUp() {
		$this->fixture = new \Mrimann\CoMo\Domain\Model\Award();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function setMonthSetsMonth() {
		$this->fixture->setMonth('2013-03');

		$this->assertEquals(
			$this->fixture->getMonth(),
			'2013-03'
		);
	}

	/**
	 * @test
	 */
	public function setTypeSetsType() {
		$this->fixture->setType('foobar');

		$this->assertEquals(
			$this->fixture->getType(),
			'foobar'
		);
	}

	/**
	 * @test
	 */
	public function setUserNameSetsUserName() {
		$this->fixture->setUserName('Max Muster');

		$this->assertEquals(
			$this->fixture->getUserName(),
			'Max Muster'
		);
	}

	/**
	 * @test
	 */
	public function setUserEmailSetsUserEmail() {
		$this->fixture->setUserEmail('foo@bar.tld');

		$this->assertEquals(
			$this->fixture->getUserEmail(),
			'foo@bar.tld'
		);
	}

	/**
	 * @test
	 */
	public function setCommitCountSetsCommitCount() {
		$this->fixture->setCommitCount(42);

		$this->assertEquals(
			42,
			$this->fixture->getCommitCount()
		);
	}
}
?>