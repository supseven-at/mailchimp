<?php

namespace Sup7even\Mailchimp\Tests\Unit\Domain\Model\Dto;

use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Exception\ApiKeyMissingException;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ExtensionConfigurationTest extends UnitTestCase
{
    /**
     * @test
     */
    public function noApiKeyThrowsException(): void
    {
        $this->expectException(ApiKeyMissingException::class);
        $config = new ExtensionConfiguration();
        $config->getApiKeys();
    }
}
