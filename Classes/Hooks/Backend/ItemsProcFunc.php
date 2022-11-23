<?php

namespace Sup7even\Mailchimp\Hooks\Backend;

use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Exception\ApiKeyMissingException;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ItemsProcFunc
{

    /** @var ExtensionConfiguration */
    protected $extensionConfiguration;

    public function __construct()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    /**
     * Get API keys and its labels
     *
     * @param array $config
     * @throws ApiKeyMissingException
     */
    public function getApiKeys(array &$config)
    {
        $keyList = $this->extensionConfiguration->getApiKeys();
        foreach ($keyList as $hash => $item) {
            $label = $this->getLanguageService()->sL($item['label']);
            $label = $label ?: $item['label'];
            $config['items'][] = [
                $label,
                $hash
            ];
        }
    }

    /**
     * Get lists
     *
     * @param array $config
     */
    public function getLists(array &$config)
    {
        $apiKeyHash = null;
        try {
            $elementId = (int)$config['row']['uid'];
            if ($elementId > 0) {
                $settings = $this->extractSettingsFromRecord($elementId);
                $apiKeyHash = $settings['apiKey'] ?? null;
            }

            $api = $this->getApiService($apiKeyHash);
            $lists = $api->getLists();
            foreach ($lists as $id => $value) {
                $title = sprintf('%s [%s]', $value, $id);
                $config['items'][] = [$title, $id];
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
        $elementId = (int)$config['row']['uid'];
        if ($elementId > 0) {
            $settings = $this->extractSettingsFromRecord($elementId);

            if ($settings['listId'] ?? false) {
                try {
                    $apiKeyHash = $settings['apiKey'] ?? null;
                    $api = $this->getApiService($apiKeyHash);
                    $interests = $api->getInterestLists($settings['listId']);
                    if (is_array($interests) && !empty($interests)) {
                        foreach ($interests as $id => $value) {
                            $config['items'][] = [$value, $id];
                        }
                    }
                } catch (\Exception $e) {
                    // do nothing
                }
            }
        }
    }

    /**
     * Get settings from given content element uid
     * @param int $elementId
     * @return array
     */
    private function extractSettingsFromRecord(int $elementId)
    {
        $contentElement = BackendUtility::getRecord('tt_content', $elementId);
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $settings = $flexFormService->convertFlexFormContentToArray($contentElement['pi_flexform']);
        if (!isset($settings['settings'])) {
            return [];
        }
        return $settings['settings'];
    }

    /**
     * @param string|null $hash
     * @return ApiService
     */
    private function getApiService(string $hash = null)
    {
        return GeneralUtility::makeInstance(ApiService::class, $hash);
    }

    /**
     * @return LanguageService
     */
    private function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
