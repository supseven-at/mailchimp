<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Mailchimp',
    'Registration',
    [
        \Sup7even\Mailchimp\Controller\FormController::class => 'index,response,ajaxResponse',
    ],
    [
        \Sup7even\Mailchimp\Controller\FormController::class => 'index,response,ajaxResponse',
    ]
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-mailchimp-wizard-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:mailchimp/Resources/Public/Icons/Extension.png']
);

// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['mailchimp_registration']['mailchimp'] =
    \Sup7even\Mailchimp\Hooks\Backend\PageLayoutViewHook::class . '->getExtensionSummary';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:mailchimp/Configuration/TSconfig/ContentElementWizard.tsconfig">');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    'module.tx_form.settings.yamlConfigurations.1627403947 = EXT:mailchimp/Configuration/Yaml/FormSetup.yaml
    plugin.tx_form.settings.yamlConfigurations.1627403947 = EXT:mailchimp/Configuration/Yaml/FormSetup.yaml'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/form']['afterBuildingFinished'][1627589455] = \Sup7even\Mailchimp\Hooks\Frontend\Form::class;
