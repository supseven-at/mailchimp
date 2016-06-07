<?php

namespace Sup7even\Mailchimp\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class FormDto extends AbstractEntity{

    /** @var string */
    protected $firstName;

    /** @var string */
    protected $lastName;

    /**
     * @var string
     * @validate NotEmpty
     * @validate EmailAddress
     */
    protected $email;

    /** @var array */
    protected $interests;

    /** @var string */
    protected $interest;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * @param array $interests
     */
    public function setInterests($interests)
    {
        $this->interests = $interests;
    }

    /**
     * @return string
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * @param string $interest
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;
    }
    

}