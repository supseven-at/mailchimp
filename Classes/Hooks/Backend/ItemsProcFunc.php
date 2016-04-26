<?php

namespace Sup7\Mailchimp\Hooks\Backend;

use Sup7\Mailchimp\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ItemsProcFunc
{
    /** @var ApiService */
    protected $api;

    public function __construct()
    {
        $this->api = GeneralUtility::makeInstance('Sup7\Mailchimp\Service\ApiService');
    }

    public function getLists(array &$config)
    {
        try {
            $lists = $this->api->getLists();
            foreach ($lists as $id => $value) {
                array_push($config['items'], array($value, $id));
            }
        } catch (\Exception $e) {
            // do nothing
        }
    }

    /**
     * Get interests of a given list
     *
     * @param array $config
     */
    public function getInterests(array &$config)
    {
        $elementId = $config['row']['uid'];

        if ((int)$elementId > 0) {
            $contentElement = BackendUtility::getRecord('tt_content', $elementId);

            /** @var \TYPO3\CMS\Extbase\Service\FlexFormService $flexFormService */
            $flexFormService = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\FlexFormService');
            $settings = $flexFormService->convertFlexFormContentToArray($contentElement['pi_flexform']);

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
}