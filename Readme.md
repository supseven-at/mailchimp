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

### Integration in EXT:formhandler`

You can integrate `formhandler` by using the following finisher:

```
[globalVar = GP:contact|newsletter = 1]
    plugin.Tx_Formhandler.settings.predef.contact {
        finishers {
            2 {
                class = Sup7\Mailchimp\Hooks\Frontend\Formhandler\Mailchimp
                config {
                    // ID of the list you want the user to be added to
                    listId = b1891812812

                    // Define the name of the field used in the form
                    fieldEmail = email
                    fieldFirstName = firstname
                    fieldLastName = lastname
                }
            }
        }
    }
[global]
```

### Prefill email address

You can prefill the email address by providing either *GET* or *POST* parameter `email`. A url could look like `http://www.domain.tld/index.php?id=758&email=admin@domain.tld`.

## Roadmap

This xtension is in its early beginnings. It is not yet defined if more features will be added or not!

## Tests

Unit Tests can be started by using

```
./typo3_src/bin/phpunit --colors  -c ./typo3/sysext/core/Build/UnitTests.xml ./typo3conf/ext/mailchimp/Tests/
```