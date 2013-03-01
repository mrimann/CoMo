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

		// TODO: Check if this is really working also on remote systems
		// Read list of repositories from the Gitweb output
		$repoList = file_get_contents($url);
		$repoLines = explode("\n", $repoList);
		$this->outputLine('Found %s repositories.', array(count($repoLines)));

		if (empty($repoLines)) {
			$this->outputLine('No repos found, exiting.');
			return;
		}

		// process each single repository
		$addedCount = 0;
		$skippedCount = 0;
		foreach ($repoLines as $repoLine) {
			// TODO: Check if we can extract the title of a repo somehow
			$title = '';
			$repoUrl = $this->buildRepoUrl($repoLine);

			if ($this->repositoryRepository->countByUrl($repoUrl) == 0) {
				$repo = new \Mrimann\CoMo\Domain\Model\Repository();
				$repo->setUrl($repoUrl);
				$repo->setTitle($title);

				// Add repository to the database
				$this->repositoryRepository->add($repo);
				$addedCount++;
				$this->outputLine('-> added repository "%s"', array($repoUrl));
			} else {
				$skippedCount++;
				$this->outputLine('-| skipped repository "%s" - exists already', array($repoUrl));
			}
		}

		// show some summary output
		$this->outputLine('Added %d new repositories, skipped %d repositories.', array($addedCount, $skippedCount));
		$this->outputLine(
			'Now having a total of %d repositories in the system of which %d are marked active to be checked.',
			array(
				$this->repositoryRepository->countAll(),
				$this->repositoryRepository->countByIsActive(TRUE)
			)
		);
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