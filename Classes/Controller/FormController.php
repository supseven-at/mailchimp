<?php

namespace Sup7even\Mailchimp\Controller;

use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FormController extends ActionController
{
    /** @var ApiService $service */
    protected $registrationService;

    public function initializeAction()
    {
        $this->registrationService = GeneralUtility::makeInstance('Sup7even\\Mailchimp\\Service\\ApiService');
    }

    /**
     * @dontvalidate $form
     */
    public function indexAction($form = null)
    {
        if (is_null($form)) {
            /** @var FormDto $form */
            $formClass = $this->settings['formClass'] ? $this->settings['formClass'] : 'Sup7even\\Mailchimp\\Domain\\Model\\Dto\\FormDto';
            $form = GeneralUtility::makeInstance($formClass);
            $prefill = GeneralUtility::_GP('email');
            if ($prefill) {
                $form->setEmail($prefill);
            }
        }

        if ($this->settings['interestId']) {
            $interests = $this->registrationService->getCategories($this->settings['listId'], $this->settings['interestId']);
        } else {
            $interests = [];
        }
        $this->view->assignMultiple(array(
            'form' => $form,
            'interests' => $interests,
        ));
    }

    /**
     * @param FormDto $form
     * @dontvalidate $form
     */
    public function ajaxResponseAction($form = null)
    {
        $this->handleRegistration($form);
    }

    /**
     * @param FormDto $form
     */
    public function responseAction($form = null)
    {
        if (is_null($form)) {
            $this->redirect('index');
        }

        $this->handleRegistration($form);
    }

    /**
     * @param FormDto|null $form
     */
    protected function handleRegistration(FormDto $form = null)
    {
        try {
            $this->registrationService->register($this->settings['listId'], $form);
        } catch (MemberExistsException $e) {
            $this->view->assign('error', 'memberExists');
        } catch (GeneralException $e) {
            $this->view->assign('error', 'general');
        }

        $this->view->assignMultiple(array(
            'form' => $form
        ));
    }
}