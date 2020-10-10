<?php

namespace Sup7even\Mailchimp\Controller;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;

class FormController extends ActionController
{

    /**
     * @param FormDto|null $form
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("form")
     */
    public function indexAction(FormDto $form = null)
    {
        if ($form === null) {
            /** @var FormDto $form */
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
     * @param FormDto|null $form
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("form")
     */
    public function ajaxResponseAction(FormDto $form = null)
    {
        $this->handleRegistration($form);
    }

    /**
     * @param FormDto|null $form
     * @throws StopActionException
     */
    public function responseAction(FormDto $form = null)
    {
        if ($form === null) {
            $this->redirect('index');
        }

        $this->handleRegistration($form);
    }

    protected function handleRegistration(FormDto $form = null)
    {
        $doublOptIn = true;
        if (isset($this->settings['skipDoubleOptIn']) && $this->settings['skipDoubleOptIn'] == 1) {
            $doublOptIn = false;
        }
        try {
            $apiService = $this->getApiService($this->settings['apiKey'] ?? '');
            $apiService->register($this->settings['listId'], $form, $doublOptIn);
        } catch (MemberExistsException $e) {
            $this->view->assign('error', 'memberExists');
        } catch (GeneralException $e) {
            $this->view->assign('error', 'general');
        }

        $this->view->assignMultiple([
            'form' => $form
        ]);
    }

    private function getApiService(string $hash = null): ApiService
    {
        return GeneralUtility::makeInstance(ApiService::class, $hash);
    }
}
