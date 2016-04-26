<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Sup7.' . $_EXTKEY,
    'Registration',
    array(
        'Form' => 'index,response'
    ),
    array(
        'Form' => 'index,response'
    )
);

if (TYPO3_MODE === 'BE') {
    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'ext-mailchimp-wizard-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        array('source' => 'EXT:mailchimp/ext_icon.png')
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:mailchimp/Configuration/TSconfig/ContentElementWizard.txt">');
