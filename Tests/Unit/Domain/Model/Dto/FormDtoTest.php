<?php

namespace Sup7even\Mailchimp\Tests\Unit\Domain\Model\Dto;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FormDtoTest extends UnitTestCase
{
    /**
     * @test
     */
    public function firstNameCanBeSet(): void
    {
        $domainModelInstance = new FormDto();
        $subject = 'Max';
        $domainModelInstance->setFirstName($subject);
        self::assertEquals($subject, $domainModelInstance->getFirstName());
    }

    /**
     * @test
     */
    public function lastNameCanBeSet(): void
    {
        $domainModelInstance = new FormDto();
        $subject = 'Mustermann';
        $domainModelInstance->setLastName($subject);
        self::assertEquals($subject, $domainModelInstance->getLastName());
    }

    /**
     * @test
     */
    public function emailCanBeSet(): void
    {
        $domainModelInstance = new FormDto();
        $subject = 'fo@bar.com';
        $domainModelInstance->setEmail($subject);
        self::assertEquals($subject, $domainModelInstance->getEmail());
    }

    /**
     * @test
     */
    public function interestsCanBeSet(): void
    {
        $domainModelInstance = new FormDto();
        $subject = ['fo', 'bar'];
        $domainModelInstance->setInterests($subject);
        self::assertEquals($subject, $domainModelInstance->getInterests());
    }

    /**
     * @test
     */
    public function interestCanBeSet(): void
    {
        $domainModelInstance = new FormDto();
        $subject = '12345';
        $domainModelInstance->setInterest($subject);
        self::assertEquals($subject, $domainModelInstance->getInterest());
    }

    /**
     * @test
     */
    public function mergeFieldsCanBeTest(): void
    {
        $domainModelInstance = new FormDto();
        for ($i = 1; $i <= 10; $i++) {
            $subject = 'content' . $i;
            $getter = 'getMergeField' . $i;
            $setter = 'setMergeField' . $i;

            $domainModelInstance->$setter($subject);
            self::assertEquals($subject, $domainModelInstance->$getter());
        }
    }
}
