# TYPO3 CMS Extension mailchimp

This extension implements the most important feature of MailChimp: Let the users register for a specific list, including interest groups!

## Requirements

- TYPO3 CMS 6.2+
- MailChimp API key
- License: GPL 2

## Installation + Configuration

1) Install the extension as any other extension.
2) Add the Mailchimp API key in the Extension Manager configuration.
3) Create a new plugin `MailChimp`on any page
4) Select a list you want the users register to and press *Save*
5) Optional: Select an interest group and save again.

## Roadmap

This extension is in its early beginnings. It is not yet defined if more features will be added or not!

## Tests

Unit Tests can be started by using

```
./typo3_src/bin/phpunit --colors  -c ./typo3/sysext/core/Build/UnitTests.xml ./typo3conf/ext/mailchimp/Tests/
```