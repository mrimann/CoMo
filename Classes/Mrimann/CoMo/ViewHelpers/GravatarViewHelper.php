<?php
namespace Mrimann\CoMo\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;


/**
 * Viewhelper to render a Gravatar Image-Tag for a given mail address.
 *
 * @Flow\Scope("prototype")
 */
class GravatarViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * Render method.
	 *
	 * @param string the mail address
	 * @param integer the size of the image, optional
	 * @return string image tag HTML
	 */
	public function render($email, $size = 80) {
		$url = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=' . $size;
		$result = '<img src="' . $url . '">';
		return $result;
	}
}
?>