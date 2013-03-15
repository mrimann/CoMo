<?php
namespace Mrimann\CoMo\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * BaseCommandController command controller for the Mrimann.CoMo package
 *
 * @Flow\Scope("singleton")
 */
class BaseCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var boolean whether the command controller should be silent and avoid CLI output
	 */
	var $quiet;

	/**
	 * Wrapper for Flow's outputLine() method that takes the --quiet option into account and does
	 * suppress all output in case quietness is requested by the lovely user.
	 *
	 * @param string the text to output
	 * @param array optional array full with stuff to be sprintf()-ed into the CLI output
	 */
	protected function outputLine($text = '', array $arguments = array()) {
		// Check if we should show something at all
		if ($this->quiet === TRUE) {
			// do not output anything
		} else {
			$this->output($text . PHP_EOL, $arguments);
		}

		return;
	}
}

?>