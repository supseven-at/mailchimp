<?php

namespace Sup7even\Mailchimp\Tests\Unit\Service;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ApiServiceTest extends BaseTestCase
{

    /**
     * @test
     */
    public function properInterestsAreReturned()
    {
        $this->markTestSkipped('not functional');
        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);
        $form = new FormDto();
        $interests = [
            '123' => 0,
            '456' => true,
            '789' => false,
            '012' => 1
        ];
        $form->setInterests($interests);
        $form->setInterest('345');
        $expected = [
            '456' => true,
            '012' => true,
            '345' => true
        ];
        $this->assertEquals($expected, $mockedApiService->_call('getInterests', $form));
    }
}
