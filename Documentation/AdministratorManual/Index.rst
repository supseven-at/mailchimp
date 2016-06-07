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


Integration in EXT:``formhandler``
----------------------------------

You can integrate `formhandler` by using the following finisher:

.. code-block:: typoscript

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
