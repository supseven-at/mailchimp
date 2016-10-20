<?php

namespace Sup7even\Mailchimp\Domain\Model\Dto;

use Sup7even\Mailchimp\Exception\ApiKeyMissingException;

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
        $settings = (array)unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp']);
        foreach ($settings as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
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
    public function getProxy() {
        return $this->proxy;
    }

    /**
     * @return string
     */
    public function getProxyPort() {
        return $this->proxyPort;
    }


}
