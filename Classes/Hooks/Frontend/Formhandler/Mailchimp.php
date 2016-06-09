<?php

namespace Sup7even\Mailchimp\Hooks\Frontend\Formhandler;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Sample implementation of a Finisher Class used by Formhandler redirecting to another page.
 * This class needs a parameter "redirect_page" to be set in TS.
 *
 * Sample configuration:
 *
 * <code>
 * finishers.4.class = Sup7even\Mailchimp\Hooks\Frontend\Formhandler\Mailchimp
 * finishers.4.config {
 *   listId = 12345
 *   fieldEmail = email
 *   fieldFirstName = first_name
 *   fieldLastName = last_name
 * }
 * </code>
 *
 */
class Mailchimp extends \Tx_Formhandler_AbstractFinisher
{

    protected $api;

    public function process()
    {
        $listId = $this->utilityFuncs->getSingle($this->settings, 'listId');
        if (empty($listId)) {
            return;
        }
        try {
            /** @var ApiService $api */
            $api = GeneralUtility::makeInstance('Sup7even\\Mailchimp\\Service\\ApiService');

            $data = $this->getData();
            $api->register($listId, $data);
        } catch (\Exception $e) {
            // do nothing
        }
        return;
    }

    /**
     * @return FormDto
     */
    protected function getData()
    {
        /** @var FormDto $data */
        $data = GeneralUtility::makeInstance('Sup7even\\Mailchimp\\Domain\\Model\\Dto\\FormDto');

        $emailField = $this->utilityFuncs->getSingle($this->settings, 'fieldEmail');
        if ($emailField && $this->gp[$emailField]) {
            $data->setEmail($this->gp[$emailField]);
        }

        $firstNameField = $this->utilityFuncs->getSingle($this->settings, 'fieldFirstName');
        if ($firstNameField && $this->gp[$firstNameField]) {
            $data->setFirstName($this->gp[$firstNameField]);
        }

        $lastNameField = $this->utilityFuncs->getSingle($this->settings, 'fieldLastName');
        if ($lastNameField && $this->gp[$lastNameField]) {
            $data->setLastName($this->gp[$lastNameField]);
        }

        return $data;
    }
}
