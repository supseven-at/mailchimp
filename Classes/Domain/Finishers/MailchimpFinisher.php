<?php

namespace Sup7even\Mailchimp\Domain\Finishers;

use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Service\ApiService;

class MailchimpFinisher extends AbstractFinisher
{
    protected function executeInternal()
    {
        /** @var ExtensionConfiguration */
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $service = $this->getApiService();
    }

    private function getApiService(string $hash = null): ApiService
    {
        return GeneralUtility::makeInstance(ApiService::class, $hash);
    }
}
