<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Sup7even.Mailchimp',
    'Registration',
    'Mailchimp',
    'ext-mailchimp-wizard-icon'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['mailchimp_registration'] = 'recursive,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['mailchimp_registration'] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('mailchimp_registration',
    'FILE:EXT:mailchimp/Configuration/FlexForms/flexform_mailchimp.xml');
