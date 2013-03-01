<?php
namespace Mrimann\CoMo\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Mrimann.CoMo".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * RepoDetectorGitwebCommand command controller for the Mrimann.CoMo package
 *
 * @Flow\Scope("singleton")
 */
class RepoDetectorGitwebCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Mrimann\CoMo\Domain\Repository\RepositoryRepository
	 * @Flow\Inject
	 */
	var $repositoryRepository;

	/**
	 * The base URL
	 *
	 * @var string
	 */
	var $baseUrl;

	/**
	 * RepoDetector for Gitweb
	 *
	 * Use this command to read a list of repos from a Gitweb "website" and add those repositories
	 * to the database for further processing afterwards.
	 *
	 * @param string $url The URL to Gitweb
	 * @param string $baseUrl The base URL that is put in front of the single repo's path
	 * @return void
	 */
	public function fetchReposCommand($url, $baseUrl) {
		if (!@fopen($url, r)) {
			throw new \TYPO3\Flow\Exception(
				sprintf(
					'Whoops - somehow I failed to open the list. Try if you can open that URL "%s"',
					$url
				)
			);
		}

		$this->baseUrl = $baseUrl;

		$this->outputLine('OK, list fetched from "%s"', array($url));
		$this->outputLine('Base-URL set to: %s', array($this->baseUrl));

		// split the list
		$list = fopen($url, 'r');

		// TODO: Check if this is really working
		while ($repoList = fread($list,50000)) {

			$repoLines = explode("\n", $repoList);
			$this->outputLine('Found %s repositories.', array(count($repoLines)));
		}

		if (empty($repoLines)) {
			$this->outputLine('No repos found, exiting.');
			return;
		}

		foreach ($repoLines as $repoLine) {
			// TODO: Check if we can extract the title of a repo somehow
			$title = '';
			$repoUrl = $this->buildRepoUrl($repoLine);

			$repo = new \Mrimann\CoMo\Domain\Model\Repository();
			$repo->setUrl($repoUrl);
			$repo->setTitle($title);

			// Add repository to the database
			// TODO: Check if that repository already exists before adding it!
			$this->repositoryRepository->add($repo);

			$this->outputLine('-> added repository "%s"', array($repoUrl));
		}
	}

	/**
	 * Puts together the path to a single git repository, based on a global
	 * base-URL and a line from the Gitweb Text-Output (which needs some
	 * tweaking before being used as path to a repo)
	 *
	 * @param $repoLine One line of the Gitweb-Textoutput
	 * @return string the full URL to the Git-Repository
	 */
	public function buildRepoUrl($repoLine) {
		$lineParts = explode(' ', $repoLine);

		$glue = '';
		if (substr($this->baseUrl, -1) != '/') {
			$glue = '/';
		}

		return $this->baseUrl . $glue . $lineParts[0];
	}
}

?>