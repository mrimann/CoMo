<?php
namespace Mrimann\CoMo\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

/**
 * Testcase for Aggregated data per user
 */
class AggregatedDataPerUserTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser
	 */
	var $fixture;

	public function setUp() {
		$this->fixture = new \Mrimann\CoMo\Domain\Model\AggregatedDataPerUser();
	}

	public function tearDown() {
		unset($this->fixture);
	}


	/**
	 * @test
	 */
	public function addCommitUpdatesTheCommitCount() {
		$this->assertEquals(
			$this->fixture->getCommitCount(),
			0
		);

		$commit = new \Mrimann\CoMo\Domain\Model\Commit();
		$this->fixture->addCommit($commit);

		$this->assertEquals(
			1,
			$this->fixture->getCommitCount()
		);
	}
}
?>