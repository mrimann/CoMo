## Why?

It started as a fun-project - and still is: One day we discussed at [internezzo](http://www.internezzo.ch/) how we can give away batches to our employees for e.g. "the committer of the month" (hence the name "CoMo").

CoMo fetches the meta data of commits from Git repositories, stores them in the database, processes them and renders some stats per person.

The stats can be integrated in a radiator screen within one's office to publicly promote the most active employee.

But it goes even further: There's a command controller that can be run e.g. once a day to fetch a list of all repositories from a remote server via the Gitweb frontend. This eases it a lot for us as newly added repositories will be detected automatically by CoMo.

## Features

- Automatically fetch a list of repositories from a Gitweb-Frontend
- Possibility to automatically detect new repositories as soon as they get added on the Git server (via Gitweb-Frontend)
- Extracts the commit's meta-data for each repository
	- only extracts the latest n days to save resources (configurable)
	- only extracts stuff that hasn't been extracted yet (e.g. the new commits since the last extraction run)
- Aggregates the extracted data
- Automatically elects the winners based on the aggregated data
- Everything can be automated via cronjobs
	- cronjobs are built in a way that they can be fired regularly and will just exit as soon as nothing is to do
- Shows several big-screen optimized views:
	- the last month's winner(s)

## Configuration options

- maxDaysToFetchFromGitLogHistory: 100

	Defines how far back in the git log we're going to extract the commits (to avoid extracting and aggregating data for e.g. 10 years of commits and then just electing the award for the last month or so).

- cacheBasePath: %FLOW_PATH_DATA%Temporary/

	Sets the base directory to be used for the local cloning of the remote repositories before the data can be extracted.

## How to contribute?

Feel free to [file new issues](https://github.com/mrimann/Mrimann.CoMo/issues) if you find a problem or to propose a new feature. If you want to contribute your time and submit a code change, I'm very eager to look at your pull request!

In case you want to discuss a new feature with me, just send me an e-mail.

## License

Licensed under the permissive [MIT license](http://opensource.org/licenses/MIT) - have fun with it!