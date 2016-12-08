<?php

namespace Sup7even\Mailchimp\Domain\Model\Dto;

use Sup7even\Mailchimp\Exception\ApiKeyMissingException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ExtensionConfiguration
{

    /** @var string */
    protected $apiKey;

    /**
     * @var string
     */
    protected $proxy = '';

    /**
     * @var string
     */
    protected $proxyPort = '';

    public function __construct()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $typoScript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT)["plugin."]["tx_mailchimp."]["settings."];

        $settings = (array)unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp']);
        foreach ($settings as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                if (empty($typoScript[$key])) {
                    $this->$key = $value;
                } else {
                    $this->$key = $typoScript[$key];
                }
            }
        }
    }

    /**
     * @return string
     * @throws ApiKeyMissingException
     */
    public function getApiKey()
    {
        if (empty($this->apiKey)) {
            throw new ApiKeyMissingException('API key is missing');
        }
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @return string
     */
    public function getProxyPort()
    {
        return $this->proxyPort;
    }

}
