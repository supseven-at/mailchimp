<?php

namespace Sup7even\Mailchimp\Hooks\Backend;

use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ItemsProcFunc
{
    /** @var ApiService */
    protected $api;

    public function __construct()
    {
        $this->api = GeneralUtility::makeInstance('Sup7even\Mailchimp\Service\ApiService');
    }

    public function getLists(array &$config)
    {
        $settings = $this->getSettings($config);
        if(!empty($settings)) {
            if ($settings['settings']["overrideApiKey"]) {
                unset($this->api);
                $this->api = GeneralUtility::makeInstance('Sup7even\Mailchimp\Service\ApiService', $settings['settings']["apiKey"]);
            }

            try {
                $lists = $this->api->getLists();
                foreach ($lists as $id => $value) {
                    $title = sprintf('%s [%s]', $value, $id);
                    array_push($config['items'], array($title, $id));
                }
            } catch (\Exception $e) {
                // do nothing
            }
        }
    }

    /**
     * Get interests of a given list
     *
     * @param array $config
     */
    public function getInterests(array &$config)
    {
        $settings = $this->getSettings($config);
        if(!empty($settings)) {
            if ($settings['settings']["overrideApiKey"]) {
                unset($this->api);
                $this->api = GeneralUtility::makeInstance('Sup7even\Mailchimp\Service\ApiService', $settings['settings']["apiKey"]);
            }

            if ($settings['settings']['listId']) {
                try {
                    $interests = $this->api->getInterestLists($settings['settings']['listId']);
                    if (is_array($interests) && !empty($interests)) {
                        foreach ($interests as $id => $value) {
                            array_push($config['items'], array($value, $id));
                        }
                    }
                } catch (\Exception $e) {
                    // do nothing
                }
            }
        }
    }

    /**
     * @param $config
     * @return array
     */
    protected function getSettings($config) {
        $elementId = $config['row']['uid'];

        if ((int)$elementId > 0) {
            $contentElement = BackendUtility::getRecord('tt_content', $elementId);

            /** @var \TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService */
            $flexFormService = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\FlexFormService');
            return $flexFormService->convertFlexFormContentToArray($contentElement['pi_flexform']);
        }
        return [];
    }
}
