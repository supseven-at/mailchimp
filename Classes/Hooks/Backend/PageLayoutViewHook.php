<?php

namespace Sup7even\Mailchimp\Hooks\Backend;

use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageLayoutViewHook
{
    /**
     * Extension key
     *
     * @var string
     */
    const KEY = 'mailchimp';

    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:mailchimp/Resources/Private/Language/locallang.xlf:';

    /**
     * Table information
     *
     * @var array
     */
    protected $tableData = [];

    /**
     * @var array
     */
    protected $flexformData = [];

    /** @var ApiService */
    protected $api;

    /** @var ExtensionConfiguration */
    protected $extensionConfiguration;

    public function getExtensionSummary(array $params = [])
    {
        try {
            $this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);
            $this->initializeApi();

            $result = '<strong>' . htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'plugin.title')) . '</strong><br>';

            $this->getApiKey();
            $this->getListInformation();
            $this->getInterestGroupInformation();
            $this->getAjaxUsage();

            $result .= $this->renderSettingsAsTable();
        } catch (\Exception $e) {
            $result = '<div class="alert alert-danger">Configuration error</div>';
        }
        return $result;
    }

    protected function getApiKey()
    {
        $apiKeyHash = $this->getFieldFromFlexform('settings.apiKey');
        if ($apiKeyHash) {
            $this->tableData[] = [
                $this->getLabel('flexform.apiKey'),
                $this->extensionConfiguration->getApiKeyLabel($apiKeyHash)
            ];
        }
    }

    protected function getAjaxUsage()
    {
        $usage = (bool)$this->getFieldFromFlexform('settings.useAjax');
        if ($usage) {
            $this->tableData[] = [
                $this->getLabel('flexform.useAjax'),
                '<i class="fa fa-check"></i>'
            ];

            if (!ExtensionManagementUtility::isLoaded('typoscript_rendering')) {
                $this->tableData[] = [
                    '',
                    '<div class="alert alert-warning typo3-message message-danger">' . $this->getLabel('ajaxEnabledWithoutExtension') . '</div>'
                ];
            }
        }
    }

    protected function getListInformation()
    {
        $listId = $this->getFieldFromFlexform('settings.listId');
        if (!$listId) {
            $this->tableData[] = [
                $this->getLabel('flexform.list'),
                '<div class="alert alert-warning">No list selected</div>'
            ];
        } else {
            $list = $this->api->getList($listId);
            $this->tableData[] = [
                $this->getLabel('flexform.list'),
                sprintf('<strong>%s</strong>', htmlspecialchars($list['name']))
            ];
            $this->tableData[] = [
                $this->getLabel('memberCount'),
                (int)$list['stats']['member_count']
            ];
        }
    }

    protected function getInterestGroupInformation()
    {
        $interestId = $this->getFieldFromFlexform('settings.interestId');
        $listId = $this->getFieldFromFlexform('settings.listId');
        if ($listId && $interestId) {
            $interests = $this->api->getCategories($listId, $interestId);

            if ($interests) {
                $this->tableData[] = [
                    $this->getLabel('flexform.interests'),
                    $interests['title']
                ];
            }
        }
    }

    /**
     * @param string $string
     * @param bool $hsc
     * @return string
     */
    protected function getLabel(string $string, bool $hsc = true)
    {
        $label = $this->getLanguageService()->sL(self::LLPATH . $string);
        if ($hsc) {
            $label = htmlspecialchars($label);
        }
        return $label;
    }

    /**
     * Return language service instance
     *
     * @return LanguageService
     */
    public function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Render the settings as table for Web>Page module
     * System settings are displayed in mono font
     *
     * @return string
     */
    protected function renderSettingsAsTable()
    {
        if (count($this->tableData) === 0) {
            return '';
        }

        $content = '';
        foreach ($this->tableData as $line) {
            $content .= ($line[0] ? ('<strong>' . $line[0] . '</strong>' . ' ') : '') . $line[1] . '<br />';
        }

        return '<pre style="white-space:normal">' . $content . '</pre>';
    }

    /**
     * Get field value from flexform configuration,
     * including checks if flexform configuration is available
     *
     * @param string $key name of the key
     * @param string $sheet name of the sheet
     * @return string|NULL if nothing found, value if found
     */
    protected function getFieldFromFlexform(string $key, $sheet = 'sDEF')
    {
        $flexform = $this->flexformData;
        if (isset($flexform['data'])) {
            $flexform = $flexform['data'];
            if (is_array($flexform) && is_array($flexform[$sheet]) && is_array($flexform[$sheet]['lDEF'])
                && is_array($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
            ) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }

        return null;
    }

    private function initializeApi()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

        $apiKeyHash = $this->getFieldFromFlexform('settings.apiKey');
        $this->api = GeneralUtility::makeInstance(ApiService::class, $apiKeyHash);
    }
}
