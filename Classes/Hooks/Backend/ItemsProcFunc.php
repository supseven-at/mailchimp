<?php

namespace Sup7even\Mailchimp\Hooks\Backend;

use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\FlexFormService;

class ItemsProcFunc
{
    /** @var ApiService */
    protected $api;

    public function __construct()
    {
        $this->api = GeneralUtility::makeInstance(ApiService::class);
    }

    public function getLists(array &$config)
    {
        try {
            $lists = $this->api->getLists();
            foreach ($lists as $id => $value) {
                $title = sprintf('%s [%s]', $value, $id);
                array_push($config['items'], [$title, $id]);
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

            $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
            $settings = $flexFormService->convertFlexFormContentToArray($contentElement['pi_flexform']);

            if ($settings['settings']['listId']) {
                try {
                    $interests = $this->api->getInterestLists($settings['settings']['listId']);
                    if (is_array($interests) && !empty($interests)) {
                        foreach ($interests as $id => $value) {
                            array_push($config['items'], [$value, $id]);
                        }
                    }
                } catch (\Exception $e) {
                    // do nothing
                }
            }
        }
    }
}
