<?php

namespace Sup7even\Mailchimp\Tests\Unit\Domain\Model\Dto;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class FormDtoTest extends UnitTestCase
{
    /**
     * @test
     */
    public function firstNameCanBeSet()
    {
        $domainModelInstance = new FormDto();
        $subject = 'Max';
        $domainModelInstance->setFirstName($subject);
        $this->assertEquals($subject, $domainModelInstance->getFirstName());
    }

    /**
     * @test
     */
    public function lastNameCanBeSet()
    {
        $domainModelInstance = new FormDto();
        $subject = 'Mustermann';
        $domainModelInstance->setLastName($subject);
        $this->assertEquals($subject, $domainModelInstance->getLastName());
    }

    /**
     * @test
     */
    public function emailCanBeSet()
    {
        $domainModelInstance = new FormDto();
        $subject = 'fo@bar.com';
        $domainModelInstance->setEmail($subject);
        $this->assertEquals($subject, $domainModelInstance->getEmail());
    }

    /**
     * @test
     */
    public function interestsCanBeSet()
    {
        $domainModelInstance = new FormDto();
        $subject = ['fo', 'bar'];
        $domainModelInstance->setInterests($subject);
        $this->assertEquals($subject, $domainModelInstance->getInterests());
    }

    /**
     * @test
     */
    public function interestCanBeSet()
    {
        $domainModelInstance = new FormDto();
        $subject = '12345';
        $domainModelInstance->setInterest($subject);
        $this->assertEquals($subject, $domainModelInstance->getInterest());
    }

    /**
     * @test
     */
    public function mergeFieldsCanBeTest()
    {
        $domainModelInstance = new FormDto();
        for ($i = 1; $i <= 10; $i++) {
            $subject = 'content' . $i;
            $getter = 'getMergeField' . $i;
            $setter = 'setMergeField' . $i;

            $domainModelInstance->$setter($subject);
            $this->assertEquals($subject, $domainModelInstance->$getter());
        }
    }
}
