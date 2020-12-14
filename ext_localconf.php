<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Sup7even.Mailchimp',
    'Registration',
    [
        'Form' => 'index,response,ajaxResponse'
    ],
    [
        'Form' => 'index,response,ajaxResponse'
    ]
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-mailchimp-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:mailchimp/ext_icon.png']
);

// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_registration'][$_EXTKEY] =
    \Sup7even\Mailchimp\Hooks\Backend\PageLayoutViewHook::class . '->getExtensionSummary';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:mailchimp/Configuration/TSconfig/ContentElementWizard.typoscript">');
