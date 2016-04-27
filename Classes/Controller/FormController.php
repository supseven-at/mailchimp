<?php

namespace Sup7\Mailchimp\Controller;

use Sup7\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7\Mailchimp\Exception\GeneralException;
use Sup7\Mailchimp\Exception\MemberExistsException;
use Sup7\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FormController extends ActionController
{
    /** @var ApiService $service */
    protected $registrationService;

    public function initializeAction()
    {
        $this->registrationService = GeneralUtility::makeInstance('Sup7\\Mailchimp\\Service\\ApiService');
    }

    /**
     * @param @dontvalidate $form
     */
    public function indexAction(FormDto $form = null)
    {
        if (is_null($form)) {
            /** @var FormDto $form */
            $form = GeneralUtility::makeInstance('Sup7\\Mailchimp\\Domain\\Model\\Dto\\FormDto');
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
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function responseAction(FormDto $form = null)
    {
        if (is_null($form)) {
            $this->redirect('index');
        }

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