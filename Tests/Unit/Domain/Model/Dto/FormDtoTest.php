<?php

namespace Sup7\Mailchimp\Tests\Unit\Domain\Model\Dto;

use Sup7\Mailchimp\Domain\Model\Dto\FormDto;
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
        $subject = array('fo', 'bar');
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
}