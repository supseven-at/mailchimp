<?php

namespace Sup7even\Mailchimp\Controller;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FormController extends ActionController
{

    /**
     * @ignorevalidation $form
     */
    public function indexAction(FormDto $form = null)
    {
        if ($form === null) {
            $form = GeneralUtility::makeInstance(FormDto::class);
            $prefill = GeneralUtility::_GP('email');
            if ($prefill) {
                $form->setEmail($prefill);
            }
        }

        $apiService = $this->getApiService($this->settings['apiKey']);

        if ($this->settings['interestId']) {
            $interests = $apiService->getCategories($this->settings['listId'], $this->settings['interestId']);
        } else {
            $interests = [];
        }
        $this->view->assignMultiple([
            'form' => $form,
            'interests' => $interests,
            'apiKey' => $apiService->getApiKey()
        ]);
    }

    /**
     * @param FormDto $form
     * @ignorevalidation $form
     */
    public function ajaxResponseAction(FormDto $form = null)
    {
        $this->handleRegistration($form);
    }

    /**
     * @param FormDto $form
     */
    public function responseAction(FormDto $form = null)
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
            $apiService = $this->getApiService($this->settings['apiKey']);
            $apiService->register($this->settings['listId'], $form);
        } catch (MemberExistsException $e) {
            $this->view->assign('error', 'memberExists');
        } catch (GeneralException $e) {
            $this->view->assign('error', 'general');
        }

        $this->view->assignMultiple([
            'form' => $form
        ]);
    }

    private function getApiService($hash = null)
    {
        return GeneralUtility::makeInstance(ApiService::class, $hash);
    }
}
