<?php

namespace Sup7\Mailchimp\Domain\Model\Dto;

class FormDto {

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
    

}