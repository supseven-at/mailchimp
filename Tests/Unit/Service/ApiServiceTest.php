<?php

namespace Sup7even\Mailchimp\Tests\Unit\Service;

use DrewM\MailChimp\MailChimp;
use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ApiServiceTest extends BaseTestCase
{

    /**
     * @test
     */
    public function apiKeyIsReturned()
    {
        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);
        $key = '1234';
        $mockedApiService->_set('apiKey', $key);
        $this->assertEquals($key, $mockedApiService->getApiKey());
    }

    /**
     * @test
     */
    public function allListsAreReturned()
    {
        $lists = [
            'lists' => [
                ['id' => 45, 'name' => 'first'],
                ['id' => 67, 'name' => 'second'],
            ]
        ];

        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);
        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['get'], [], '', false);
        $mockedApi->expects($this->once())->method('get')->willReturn($lists);
        $mockedApiService->_set('api', $mockedApi);

        $expected = [45 => 'first', 67 => 'second'];
        $this->assertEquals($expected, $mockedApiService->getLists());
    }

    /**
     * @test
     */
    public function listReturned()
    {
        $lists = ['id' => 45, 'name' => 'first'];

        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);
        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['get'], [], '', false);
        $mockedApi->expects($this->once())->method('get')->with('lists/45')->willReturn($lists);
        $mockedApiService->_set('api', $mockedApi);

        $this->assertEquals($lists, $mockedApiService->getList('45'));
    }

    /**
     * @test
     */
    public function interestListsAreReturned()
    {
        $lists = [
            'categories' => [
                ['id' => 89, 'title' => 'first'],
                ['id' => 12, 'title' => 'second'],
            ]
        ];

        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);
        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['get'], [], '', false);
        $mockedApi->expects($this->once())->method('get')->with('lists/88888/interest-categories/')->willReturn($lists);
        $mockedApiService->_set('api', $mockedApi);

        $expected = [89 => 'first', 12 => 'second'];
        $this->assertEquals($expected, $mockedApiService->getInterestLists('88888'));
    }

    /**
     * @test
     */
    public function categoriesAreReturned()
    {
        $groupData = ['title' => 'title1', 'type' => 'someType'];
        $interests = [
            'interests' => [
                ['id' => 89, 'name' => 'option1'],
            ]
        ];
        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);
        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['get'], [], '', false);
        $mockedApi->expects($this->any())->method('get')
            ->withConsecutive(['lists/111/interest-categories/222/'], ['lists/111/interest-categories/222/interests'])
            ->willReturnOnConsecutiveCalls($groupData, $interests);
        $mockedApiService->_set('api', $mockedApi);

        $expected = [
            'title' => 'title1',
            'type' => 'someType',
            'options' => [
                89 => 'option1'
            ]
        ];
        $this->assertEquals($expected, $mockedApiService->getCategories('111', '222'));
    }


    /**
     * @test
     */
    public function registerCallThrowsGeneralException()
    {
        $data = ['FO' => 'bar', 'status' => 400];
        $this->expectException(GeneralException::class);
        $mockedLogger = $this->getAccessibleMock(Logger::class, ['dummy'], [], '', false);

        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['getRegistrationData'], [], '', false);
        $mockedApiService->_set('logger', $mockedLogger);
        $mockedApiService->expects($this->once())->method('getRegistrationData')->willReturn([]);

        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['post'], [], '', false);
        $mockedApi->expects($this->once())->method('post')->with('lists/456/members')->willReturn($data);
        $mockedApiService->_set('api', $mockedApi);

        $form = new FormDto();
        $mockedApiService->register('456', $form);
    }

    /**
     * @test
     */
    public function registerCallThrowsExceptionForRegistered()
    {
        $data = ['FO' => 'bar', 'status' => 401, 'title' => 'Member Exists'];
        $this->expectException(MemberExistsException::class);
        $mockedLogger = $this->getAccessibleMock(Logger::class, ['dummy'], [], '', false);

        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['getRegistrationData'], [], '', false);
        $mockedApiService->_set('logger', $mockedLogger);
        $mockedApiService->expects($this->once())->method('getRegistrationData')->willReturn([]);

        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['post', 'put', 'get', 'subscriberHash'], [], '', false);
        $mockedApi->expects($this->once())->method('subscriberHash')->willReturn('a-hash');
        $mockedApi->expects($this->once())->method('get')->willReturn(['status' => 'subscribed']);
        $mockedApi->expects($this->once())->method('post')->willReturn($data);
        $mockedApiService->_set('api', $mockedApi);

        $form = new FormDto();
        $mockedApiService->register('456', $form);
    }

    /**
     * @test
     */
    public function registerCallWorks()
    {
        $data = ['FO' => 'bar', 'status' => 401, 'title' => 'Member Exists'];
        $mockedLogger = $this->getAccessibleMock(Logger::class, ['dummy'], [], '', false);

        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['getRegistrationData'], [], '', false);
        $mockedApiService->_set('logger', $mockedLogger);
        $mockedApiService->expects($this->once())->method('getRegistrationData')->willReturn(['some array']);

        $mockedApi = $this->getAccessibleMock(MailChimp::class, ['post', 'put', 'get', 'subscriberHash'], [], '', false);
        $mockedApi->expects($this->any())->method('subscriberHash')->willReturn('a-hash');
        $mockedApi->expects($this->once())->method('get')->willReturn(['status' => 'NOT subscribed']);
        $mockedApi->expects($this->once())->method('post')->willReturn($data);
        $mockedApi->expects($this->once())->method('put')->with('lists/456/members/a-hash', ['some array']);
        $mockedApiService->_set('api', $mockedApi);

        $form = new FormDto();
        $mockedApiService->register('456', $form);
    }

    /**
     * @test
     */
    public function properInterestsAreReturned()
    {
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

    /**
     * @test
     */
    public function registrationDataIsReturned()
    {
        $mockedApiService = $this->getAccessibleMock(ApiService::class, ['dummy'], [], '', false);

        $form = new FormDto();
        $form->setFirstName('John');
        $form->setLastName('Doe');
        $form->setEmail('john@doe.at');
        $form->setInterests(['bla' => true, 'fo' => false]);

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData']['dummy'] = function ($parameters, $reference) use ($inputData) {
            return 'Worked fine';
        };

        $data = $mockedApiService->_call('getRegistrationData', '123', $form);
        $expected = [
            'email_address' => 'john@doe.at',
            'status' => 'pending',
            'merge_fields' => [
                'FNAME' => 'John',
                'LNAME' => 'Doe',
            ],
            'interests' => [
                'bla' => true
            ]
        ];


        $this->assertEquals($expected, $data);
    }
}
