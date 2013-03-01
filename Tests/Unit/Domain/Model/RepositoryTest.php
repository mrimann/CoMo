<?php
namespace Mrimann\CoMo\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

/**
 * Testcase for Repository
 */
class RepositoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Mrimann\CoMo\Domain\Model\Repository
	 */
	var $fixture;

	public function setUp() {
		$this->fixture = new \Mrimann\CoMo\Domain\Model\Repository();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function setUrlSetsUrl() {
		$this->fixture->setUrl('http://foo/bar.git');
		$this->assertEquals(
			'http://foo/bar.git',
			$this->fixture->getUrl()
		);
	}

	/**
	 * @test
	 */
	public function getIsActiveIsFalseByDefault() {
		$this->assertFalse(
			$this->fixture->getIsActive()
		);
	}

	/**
	 * @test
	 */
	public function setIsActiveSetsActiveFlag() {
		$this->fixture->setIsActive(TRUE);
		$this->assertTrue(
			$this->fixture->getIsActive()
		);
	}
}
?>