<?php

namespace Sup7even\Mailchimp\Domain\Model\Dto;

use Sup7even\Mailchimp\Exception\ApiKeyMissingException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionConfiguration implements SingletonInterface
{

    /** @var array */
    protected $apiKeys = [];

    /** @var string */
    protected $proxy = '';

    /** @var string */
    protected $proxyPort = '';

    /** @var bool */
    protected $forceIp4 = false;

    public function __construct()
    {
        $settings = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('mailchimp');

        $this->setApiKeys($settings['apiKey'])
            ->setProxy($settings['proxy'])
            ->setProxyPort($settings['proxyPort']);
        $this->forceIp4 = (bool)$settings['forceIp4'];
    }

    /**
     * Get all API key configurations
     *
     * @return array
     * @throws ApiKeyMissingException
     */
    public function getApiKeys()
    {
        if (empty($this->apiKeys)) {
            throw new ApiKeyMissingException('API key is missing');
        }
        return $this->apiKeys;
    }

    /**
     * Get 1st API key configuration
     *
     * @return string
     */
    public function getFirstApiKey()
    {
        $firstItem = current($this->apiKeys);
        return $firstItem['key'] ?? '';
    }

    /**
     * @param string $hash
     * @return string
     */
    public function getApiKeyByHash(string $hash)
    {
        $settings = $this->getApiKeyConfiguration($hash);
        return $settings['key'];
    }

    /**
     * @param string $hash
     * @return string
     */
    public function getApiKeyLabel(string $hash)
    {
        $settings = $this->getApiKeyConfiguration($hash);
        return $settings['label'];
    }

    /**
     * @param string $hash
     * @return array
     */
    private function getApiKeyConfiguration(string $hash)
    {
        if (!isset($this->apiKeys[$hash])) {
            throw new \UnexpectedValueException(sprintf('For hash "%s" no API key found', $hash), 1513232660);
        }
        return $this->apiKeys[$hash];
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

    /**
     * @param string $apiKey
     * @return ExtensionConfiguration
     */
    private function setApiKeys(string $apiKey)
    {
        $keys = GeneralUtility::trimExplode(',', $apiKey, true);
        if (count($keys) === 1) {
            $split = GeneralUtility::trimExplode(':', $keys[0], true, 2);
            if (count($split) === 1) {
                $this->addApiKey($split[0]);
            } else {
                $this->addApiKey($split[0], $split[1]);
            }
        } else {
            foreach ($keys as $key) {
                $split = GeneralUtility::trimExplode(':', $key, true, 2);
                if (count($split) === 1) {
                    $this->addApiKey($split[0]);
                } else {
                    $this->addApiKey($split[0], $split[1]);
                }
            }
        }
        return $this;
    }

    private function addApiKey($key, $label = 'default')
    {
        $hash = md5($key);
        $this->apiKeys[$hash] = [
            'key' => $key,
            'label' => $label
        ];
    }

    /**
     * @param string $proxy
     * @return ExtensionConfiguration
     */
    private function setProxy(string $proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @param string $proxyPort
     * @return ExtensionConfiguration
     */
    private function setProxyPort(string $proxyPort)
    {
        $this->proxyPort = $proxyPort;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForceIp4(): bool
    {
        return $this->forceIp4;
    }

}
