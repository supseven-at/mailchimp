<?php

namespace Sup7even\Mailchimp\Tests\Unit\Domain\Model\Dto;

use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Exception\ApiKeyMissingException;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ExtensionConfigurationTest extends BaseTestCase
{


    /**
     * @test
     */
    public function noApiKeyThrowsException()
    {
        $this->expectException(ApiKeyMissingException::class);
        $config = new ExtensionConfiguration();
        $config->getApiKeys();
    }

    /**
     * @test
     */
    public function configurationIsReturned()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp'] = serialize([
            'apiKey' => 'key',
            'proxy' => 'a proxy',
            'proxyPort' => '123'
        ]);
        $subject = new ExtensionConfiguration();
        $keys = $subject->getApiKeys();
        $this->assertEquals(['key' => 'key', 'label' => 'default'], current($keys));
        $this->assertEquals('a proxy', $subject->getProxy());
        $this->assertEquals('123', $subject->getProxyPort());
    }

    /**
     * @test
     */
    public function multiKeysAreReturned()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp'] = serialize([
            'apiKey' => 'key:fo',
            'proxy' => 'a proxy',
            'proxyPort' => '123'
        ]);
        $subject = new ExtensionConfiguration();
        $key = $subject->getFirstApiKey();
        $this->assertEquals('key', $key);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp'] = serialize([
            'apiKey' => 'keyxxx:fo,key2:label2',
            'proxy' => 'a proxy',
            'proxyPort' => '123'
        ]);
        $subject = new ExtensionConfiguration();
        $key = $subject->getApiKeyByHash(md5('key2'));
        $this->assertEquals('key2', $key);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp'] = serialize([
            'apiKey' => 'keyxxx:fo,key2',
            'proxy' => 'a proxy',
            'proxyPort' => '123'
        ]);
        $subject = new ExtensionConfiguration();
        $key = $subject->getApiKeyByHash(md5('key2'));
        $this->assertEquals('key2', $key);
        $this->assertEquals('fo', $subject->getApiKeyLabel(md5('keyxxx')));
    }


    /**
     * @test
     */
    public function missingKeyThrowsException()
    {

        $this->expectException(\UnexpectedValueException::class);
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp'] = serialize([
            'apiKey' => 'keyxxx:fo,key2:label2',
            'proxy' => 'a proxy',
            'proxyPort' => '123'
        ]);
        $subject = new ExtensionConfiguration();
        $subject->getApiKeyByHash(md5('key3'));
    }
}
