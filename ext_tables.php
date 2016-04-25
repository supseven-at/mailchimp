<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Sup7.' . $_EXTKEY,
	'Registration',
	'Mailchimp'
);
