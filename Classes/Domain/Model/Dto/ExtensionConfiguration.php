<?php

namespace Sup7even\Mailchimp\Domain\Model\Dto;

use Sup7even\Mailchimp\Exception\ApiKeyMissingException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionConfiguration
{
    protected array $apiKeys = [];
    protected string $proxy = '';
    protected string $proxyPort = '';
    protected bool $forceIp4 = false;

    public function __construct()
    {
        $settings = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('mailchimp');

        $this->setApiKeys($settings['apiKey'] ?? '')
            ->setProxy($settings['proxy'] ?? '')
            ->setProxyPort($settings['proxyPort'] ?? '');
        $this->forceIp4 = (bool)($settings['forceIp4'] ?? false);
    }

    public function getApiKeys(): array
    {
        if (empty($this->apiKeys)) {
            throw new ApiKeyMissingException('API key is missing');
        }
        return $this->apiKeys;
    }

    public function getFirstApiKey(): string
    {
        $firstItem = current($this->apiKeys);
        return $firstItem['key'] ?? '';
    }

    public function getApiKeyByHash(string $hash): string
    {
        $settings = $this->getApiKeyConfiguration($hash);
        return $settings['key'];
    }

    public function getApiKeyLabel(string $hash): string
    {
        $settings = $this->getApiKeyConfiguration($hash);
        return $settings['label'];
    }

    private function getApiKeyConfiguration(string $hash): array
    {
        if (!isset($this->apiKeys[$hash])) {
            throw new \UnexpectedValueException(sprintf('For hash "%s" no API key found', $hash), 1513232660);
        }
        return $this->apiKeys[$hash];
    }

    public function getProxy(): string
    {
        return $this->proxy;
    }

    public function getProxyPort(): string
    {
        return $this->proxyPort;
    }

    private function setApiKeys(string $apiKey): self
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

    private function addApiKey($key, $label = 'default'): void
    {
        $hash = md5($key);
        $this->apiKeys[$hash] = [
            'key' => $key,
            'label' => $label,
        ];
    }

    private function setProxy(string $proxy): self
    {
        $this->proxy = $proxy;
        return $this;
    }

    private function setProxyPort(string $proxyPort): self
    {
        $this->proxyPort = $proxyPort;
        return $this;
    }

    public function isForceIp4(): bool
    {
        return $this->forceIp4;
    }
}
