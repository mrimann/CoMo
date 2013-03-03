<?php
namespace Mrimann\CoMo\Tests\ViewHelpers;

	/*                                                                        *
	 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
	 *                                                                        *
	 *                                                                        */

/**
 * Testcase for the GravatarViewHelper
 *
 */
class GravatarViewHelperTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * var \Internezzo\Grill\ViewHelpers\GravatarViewHelper
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Mrimann\CoMo\ViewHelpers\GravatarViewHelper();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function renderContainsProperUrlWithDefaultSize() {
		$this->assertContains(
			'http://www.gravatar.com/avatar/c80704af798af22df1acccf734568185?s=80',
			$this->fixture->render('mario@rimann.org')
		);
	}

	/**
	 * @test
	 */
	public function renderContainsProperUrlWithIndividualSize() {
		$this->assertContains(
			'http://www.gravatar.com/avatar/c80704af798af22df1acccf734568185?s=100',
			$this->fixture->render('mario@rimann.org', 100)
		);
	}
}
?>