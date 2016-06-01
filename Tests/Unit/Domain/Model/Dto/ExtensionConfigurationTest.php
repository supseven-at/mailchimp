<?php

namespace Sup7\Mailchimp\Tests\Unit\Domain\Model\Dto;

use Sup7\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7\Mailchimp\Exception\ApiKeyMissingException;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ExtensionConfigurationTest extends UnitTestCase
{
    /**
     * @test
     */
    public function noApiKeyThrowsException()
    {
        $this->expectException(ApiKeyMissingException::class);
        $config = new ExtensionConfiguration();
        $config->getApiKey();
    }

    /**
     * @test
     */
    public function apiKeyIsReturned()
    {
        $key = 'test123';
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mailchimp'] = serialize(array('apiKey' => $key));
        $config = new ExtensionConfiguration();
        $this->assertEquals($config->getApiKey(), $key);
    }

}