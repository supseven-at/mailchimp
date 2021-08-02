<?php

namespace Sup7even\Mailchimp\Domain\Model\FormElements;

use TYPO3\CMS\Form\Domain\Model\FormElements\AbstractFormElement;
use TYPO3\CMS\Form\Domain\Model\FormElements\Section;
use TYPO3\CMS\Form\Domain\Model\FormElements\Page;

class Interests extends AbstractFormElement
{
    public function initializeFormElement()
    {
        parent::initializeFormElement();
    }

    public function useGroupNameAsLabel()
    {
        return $this->properties['useGroupName'];
    }
}