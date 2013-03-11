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
			0,
			$this->fixture->getCommitCount()
		);

		$commit = new \Mrimann\CoMo\Domain\Model\Commit();
		$this->fixture->addCommit($commit);

		$this->assertEquals(
			1,
			$this->fixture->getCommitCount()
		);
	}

	/**
	 * @test
	 */
	public function addCommitIncreasesTopicCounter() {
		$this->assertEquals(
			0,
			$this->fixture->getCommitCountBugfix()
		);

		$commit = new \Mrimann\CoMo\Domain\Model\Commit();
		$commit->setCommitLine('[BUGFIX] foo bar');
		$this->fixture->addCommit($commit);

		$this->assertEquals(
			1,
			$this->fixture->getCommitCountBugfix()
		);
	}

	/**
	 * @test
	 */
	public function addCommitDoesNotIncreaseWrongTopicCount() {
		$this->assertEquals(
			0,
			$this->fixture->getCommitCountBugfix()
		);

		$commit = new \Mrimann\CoMo\Domain\Model\Commit();
		$commit->setCommitLine('[FEATURE] foo bar');
		$this->fixture->addCommit($commit);

		$this->assertEquals(
			0,
			$this->fixture->getCommitCountBugfix()
		);
	}

	/**
	 * @test
	 */
	public function addCommitIncreasesUnknownCountWithNoTopicPrefixInCommitMessage() {
		$this->assertEquals(
			0,
			$this->fixture->getCommitCountUnknown()
		);

		$commit = new \Mrimann\CoMo\Domain\Model\Commit();
		$commit->setCommitLine('foo bar');
		$this->fixture->addCommit($commit);

		$this->assertEquals(
			1,
			$this->fixture->getCommitCountUnknown()
		);
	}

	/**
	 * @test
	 */
	public function addCommitIncreasesUnknownCountWithPrefixThatIsNotKnownInCommitMessage() {
		$this->assertEquals(
			0,
			$this->fixture->getCommitCountUnknown()
		);

		$commit = new \Mrimann\CoMo\Domain\Model\Commit();
		$commit->setCommitLine('[XYZ] foo bar');
		$this->fixture->addCommit($commit);

		$this->assertEquals(
			1,
			$this->fixture->getCommitCountUnknown()
		);
	}
}
?>