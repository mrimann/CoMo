## Why?

It started as a fun-project - and still is: One day we discussed at [internezzo](http://www.internezzo.ch/) how we can give away an award to our employees for e.g. "the committer of the month" (hence the name "CoMo").

CoMo fetches the meta data of commits from Git repositories, stores them in the database, aggregates them and renders some stats per person (this part and some trending stuff will be added soon).

The main screen looks like this:

![Screenshot of CoMo in action](https://raw.github.com/mrimann/CoMo/master/Documentation/Screenshots/coderOfTheMonth.png)

The stats can be integrated in a radiator screen within one's office to publicly promote the most active employee. To show different tool's output and "flip" through those pages one after each other, we've additionally built a small wrapper called "[radiate](https://github.com/mrimann/radiate)"

But it goes even further: There's a command controller that can be run e.g. once a day to fetch a list of all repositories from a remote server via the Gitweb frontend. This eases it a lot for us as newly added repositories will be detected automatically by CoMo.

Regarding the repositories: Remote repositories will be cloned the first time, then just the changes will be pulled down to the local clone to save bandwith and processing time. For repos that are located locally (URL begins with ``file://``), no clone is made, their log get's directly read from the original repo location.

## Does it make sense?

Well, basically one can create a ton of smallish commits and he'll be the winner every month. That's true and of course a fact. CoMo is really more about the fun. But if you and your team is running via a code-review system (e.g. Gerrit) before committing to your master branch, the number of "merged-to-master"-changes get's more relevant of course (and you'll directly see those black sheeps that try to cheat with small commits)

## Feature list

- Automatically fetch a list of repositories from a Gitweb-Frontend (can be run e.g. daily to detect new repos)
- Extracts the commit's meta-data for each repository
	- only extracts the latest n days to save resources (configurable)
	- only extracts stuff that hasn't been extracted yet (e.g. the new commits since the last extraction run)
- Aggregates the extracted data
- Automatically elects the winners based on the aggregated data
- Sends a notification e-mail to the ceremony master upon electing a new award (so he can hand over the physical award to the winner)
- Everything can be automated via cronjobs
	- cronjobs are built in a way that they can be fired regularly and will just exit as soon as nothing is to do
	- they have a "be quiet" flag
- Shows several big-screen optimized views:
	- the last month's winner(s)
- If the committer's e-mail address has a gravatar image, this one will be shown - or a fallback pixel-image if there's no portrait

## Configuration options

- maxDaysToFetchFromGitLogHistory: 100

	Defines how far back in the git log we're going to extract the commits (to avoid extracting and aggregating data for e.g. 10 years of commits and then just electing the award for the last month or so).

- cacheBasePath: %FLOW_PATH_DATA%Temporary/

	Sets the base directory to be used for the local cloning of the remote repositories before the data can be extracted.

- sendNotificationMailsForCoderOfTheMonth: FALSE

	Whether to send mails to the ceremony master upon election of a new coder of the month

- sendNotificationMailsForTopicAwardWinner: FALSE

	Whether to send mails to the ceremony master upon election of a new topic award winner

- ceremonyMasterEmail: mario+como@rimann.org

	The e-mail address of the ceremony master (get's notifications upon newly elected awards)

- senderEmail: como@example.org

	The sender e-mail address

- senderName: CoMo

	The sender name of the mails


## Getting it set-up

To get the system up and running, of course you need to have a TYPO3 Flow setup and install this package into it (it's [on packagist as mrimann/como](https://packagist.org/packages/mrimann/como). Then you need to execute the following commands on the commandline - or even better run them via crontab to let the system do the work on it's own:

### Adding new repositories

A list of repositories to monitor is kept in the database. If you run a server that hosts your Git repositories and have a Gitweb-Frontend installed, *CoMo* can actually get a list of all repos e.g. once daily and automatically add new repositories that have been created so they get monitored, too.

	./flow help repodetectorgitweb:fetchrepos

This will show you all the options on how to use this command.


### Processing the data

There are two commands that do the whole data-processing-stuff. Just execute the following two commands to get stuff processed:

	./flow metadataextractor:processrepositories
	./flow dataaggregator:processcommits

### Electing the Coder of the Month

Based on the now processed (aggregated) data, *CoMo* can now elect the Coder of the Month if you execute the following command:

	./flow election:electlastmonth

This will elect the coder of the month for the last calendar month (since the current month is not over yet, it can't be elected yet).


## How to contribute?

Feel free to [file new issues](https://github.com/mrimann/Mrimann.CoMo/issues) if you find a problem or to propose a new feature. If you want to contribute your time and submit a code change, I'm very eager to look at your pull request!

In case you want to discuss a new feature with me, just send me an e-mail.

## Want to make me happy?

Here's [a list](http://www.rimann.org/support) of things to make me happy and that will keep me doing stuff like this in the future :-)

## License

Licensed under the permissive [MIT license](http://opensource.org/licenses/MIT) - have fun with it!