<?php
namespace Mrimann\CoMo\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

/**
 * Testcase for Commit
 */
class CommitTest extends \TYPO3\Flow\Tests\UnitTestCase {
	/**
	 * @var \Mrimann\CoMo\Domain\Model\Commit
	 */
	var $fixture;

	public function setUp() {
		$this->fixture = new \Mrimann\CoMo\Domain\Model\Commit();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function setRepositorySetsRepository() {
		$repository = new \Mrimann\CoMo\Domain\Model\Repository();

		$this->fixture->setRepository($repository);
		$this->assertSame(
			$this->fixture->getRepository(),
			$repository
		);
	}

	/**
	 * @test
	 */
	public function setHashSetsHash() {
		$this->fixture->setHash('foo');
		$this->assertEquals(
			$this->fixture->getHash(),
			'foo'
		);
	}

	/**
	 * @test
	 */
	public function setAuthorNameSetsAuthorName() {
		$this->fixture->setAuthorName('Max Muster');
		$this->assertEquals(
			$this->fixture->getAuthorName(),
			'Max Muster'
		);
	}

	/**
	 * @test
	 */
	public function setAuthorEmailSetsAuthorEmail() {
		$this->fixture->setAuthorEmail('foo@bar.tld');
		$this->assertEquals(
			$this->fixture->getAuthorEmail(),
			'foo@bar.tld'
		);
	}

	/**
	 * @test
	 */
	public function setCommitterNameSetsCommitterName() {
		$this->fixture->setCommitterName('Max Muster');
		$this->assertEquals(
			$this->fixture->getCommitterName(),
			'Max Muster'
		);
	}

	/**
	 * @test
	 */
	public function setCommitterEmailSetsCommitterEmail() {
		$this->fixture->setCommitterEmail('foo@bar.tld');
		$this->assertEquals(
			$this->fixture->getCommitterEmail(),
			'foo@bar.tld'
		);
	}

	/**
	 * @test
	 */
	public function setCommitLineSetsCommitLine() {
		$this->fixture->setCommitLine('[TEST] Blubb');
		$this->assertEquals(
			'[TEST] Blubb',
			$this->fixture->getCommitLine()
		);
	}

	/**
	 * @test
	 */
	public function setCommitLineSetsCommitClassToUnknown() {
		$this->fixture->setCommitLine('Foobar changed');
		$this->assertEquals(
			\Mrimann\CoMo\Domain\Model\Commit::CLASS_UNKNOWN,
			$this->fixture->getCommitClass()
		);
	}

	/**
	 * @test
	 */
	public function setCommitLineSetsCommitClassToTask() {
		$this->fixture->setCommitLine('[TASK] Foobar changed');
		$this->assertEquals(
			\Mrimann\CoMo\Domain\Model\Commit::CLASS_TASK,
			$this->fixture->getCommitClass()
		);
	}

	/**
	 * @test
	 */
	public function setCommitLineSetsCommitClassEvenIfStrangelyWritten() {
		$this->fixture->setCommitLine('[bugFIX] Foobar fixed');
		$this->assertEquals(
			\Mrimann\CoMo\Domain\Model\Commit::CLASS_BUGFIX,
			$this->fixture->getCommitClass()
		);
	}

	/**
	 * @test
	 */
	public function getMonthIdentifierReturnsProperValue() {
		$date = new \DateTime('2013-03-01');
		$this->fixture->setDate($date);

		$this->assertEquals(
			$this->fixture->getMonthIdentifier(),
			'2013-03'
		);
	}
}
?>