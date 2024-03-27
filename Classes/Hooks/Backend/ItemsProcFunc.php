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
    protected ExtensionConfiguration $extensionConfiguration;

    public function __construct()
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    /**
     * Get API keys and its labels
     *
     * @throws ApiKeyMissingException
     */
    public function getApiKeys(array &$config): void
    {
        $keyList = $this->extensionConfiguration->getApiKeys();
        foreach ($keyList as $hash => $item) {
            $label = $this->getLanguageService()->sL($item['label']);
            $label = $label ?: $item['label'];
            $config['items'][] = [
                $label,
                $hash,
            ];
        }
    }

    public function getLists(array &$config): void
    {
        $apiKeyHash = null;
        try {
            $elementId = (int)$config['row']['uid'];
            if ($elementId > 0) {
                $settings = $this->extractSettingsFromRecord($elementId);
                $settings['apiKey'] = $settings['apiKey'] ?? $settings['finishers']['Mailchimp']['api_key'] ?? null;
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

    public function getInterests(array &$config): void
    {
        $elementId = (int)$config['row']['uid'];
        if ($elementId > 0) {
            $settings = $this->extractSettingsFromRecord($elementId);
            $settings['listId'] = $settings['listId'] ?? $settings['finishers']['Mailchimp']['list_id'] ?? null;

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
     */
    private function extractSettingsFromRecord(int $elementId): array
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

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
