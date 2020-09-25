.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _users-manual:

Administrator manual
====================

This section containts a collection of helpful snippets you can use to impove the mailchimp registration.

.. only:: html

.. contents::
        :local:
        :depth: 1

Change the templates
--------------------
If you want to change the templates, copy those to your site extension or any other directory and specify the path.

.. code-block:: typoscript

        plugin.tx_mailchimp {
            view {
                templateRootPaths {
                    0 = typo3conf/ext/mailchimp/Resources/Private/Templates/
                    1 = typo3conf/ext/<your-extkey>/Resources/Private/Templates/
                }
                partialRootPaths {
                    0 = typo3conf/ext/mailchimp/Resources/Private/Partials/
                    1 = typo3conf/ext/<your-extkey>/Resources/Private/Partials/
                }
                layoutRootPaths {
                    0 = typo3conf/ext/mailchimp/Resources/Private/Layouts/
                    1 = typo3conf/ext/<your-extkey>/Resources/Private/Layouts/
                }
            }
        }


Prefill email address
---------------------

You can fill the email address field by providing either *GET* or *POST* parameter ``email``.
An url could look like ``http://www.domain.tld/index.php?id=758&email=admin@domain.tld``.

Translate group categories
--------------------------

To be able to translate groups of MailChimp, please use TypoScript. The key is the name of the category, umlauts are transformed and all characters except numbers and letters are stripped away.

As an example: The string `Öffentliche Verwaltung & Technologie` will be transformed to `OeffentlicheVerwaltungTechnologie``.

.. code-block:: typoscript

        plugin.tx_mailchimp {
            _LOCAL_LANG {
                de {
                }
                en {
                    interest.OeffentlicheVerwaltung = Civil Service
                }
            }
        }

Handling of custom fields
-------------------------
It is possible to handle additional fields in the mailchimp extension. Uo to 10 fields can be used in the template which are named ``mergeField1`` to ``mergeField10``. The mapping to the actual fields must be done in a custom hook implementation. Use the following hook

.. code-block:: php

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData'][]
            = 'Vendor\Extension\Hooks\MailchimpHook->run';

.. warning:: Currently there is no server side validation possible if you set fields in the MailChimp interface to required!

Skip double opt in
------------------
If double opt-in is disabled in MailChimp itself, it can also be disabled in the extension using

.. code-block:: typoscript

   plugin.tx_mailchimp.settings.skipDoubleOptIn = 1

.. tip:: You should always use double opt in
