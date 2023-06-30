<?php

namespace Sup7even\Mailchimp\Domain\Model\Dto;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class FormDto extends AbstractEntity
{
    protected string $firstName = '';
    protected string $lastName = '';

    /**
     * @Validate("NotEmpty")
     * @Validate("EmailAddress")
     */
    protected string $email = '';
    protected array $interests = [];
    protected string $interest = '';
    protected string $mergeField1 = '';
    protected string $mergeField2 = '';
    protected string $mergeField3 = '';
    protected string $mergeField4 = '';
    protected string $mergeField5 = '';
    protected string $mergeField6 = '';
    protected string $mergeField7 = '';
    protected string $mergeField8 = '';
    protected string $mergeField9 = '';
    protected string $mergeField10 = '';
    protected string $formName = '';

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getInterests(): array
    {
        return $this->interests;
    }

    public function setInterests(array $interests): void
    {
        $this->interests = $interests;
    }

    public function getInterest(): string
    {
        return $this->interest;
    }

    public function setInterest(string $interest): void
    {
        $this->interest = $interest;
    }

    public function getMergeField1(): string
    {
        return $this->mergeField1;
    }

    public function setMergeField1(string $mergeField1): void
    {
        $this->mergeField1 = $mergeField1;
    }

    public function getMergeField2(): string
    {
        return $this->mergeField2;
    }

    public function setMergeField2(string $mergeField2): void
    {
        $this->mergeField2 = $mergeField2;
    }

    public function getMergeField3(): string
    {
        return $this->mergeField3;
    }

    public function setMergeField3(string $mergeField3): void
    {
        $this->mergeField3 = $mergeField3;
    }

    public function getMergeField4(): string
    {
        return $this->mergeField4;
    }

    public function setMergeField4(string $mergeField4): void
    {
        $this->mergeField4 = $mergeField4;
    }

    public function getMergeField5(): string
    {
        return $this->mergeField5;
    }

    public function setMergeField5(string $mergeField5): void
    {
        $this->mergeField5 = $mergeField5;
    }

    public function getMergeField6(): string
    {
        return $this->mergeField6;
    }

    public function setMergeField6(string $mergeField6): void
    {
        $this->mergeField6 = $mergeField6;
    }

    public function getMergeField7(): string
    {
        return $this->mergeField7;
    }

    public function setMergeField7(string $mergeField7): void
    {
        $this->mergeField7 = $mergeField7;
    }

    public function getMergeField8(): string
    {
        return $this->mergeField8;
    }

    public function setMergeField8(string $mergeField8): void
    {
        $this->mergeField8 = $mergeField8;
    }

    public function getMergeField9(): string
    {
        return $this->mergeField9;
    }

    public function setMergeField9(string $mergeField9): void
    {
        $this->mergeField9 = $mergeField9;
    }

    public function getMergeField10(): string
    {
        return $this->mergeField10;
    }

    public function setMergeField10(string $mergeField10): void
    {
        $this->mergeField10 = $mergeField10;
    }

    public function getFormName(): string
    {
        return $this->formName;
    }

    public function setFormName(string $formName): void
    {
        $this->formName = $formName;
    }
}
