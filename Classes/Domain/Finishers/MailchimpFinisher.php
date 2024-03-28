<?php

namespace Sup7even\Mailchimp\Domain\Finishers;

use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use Sup7even\Mailchimp\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

class MailchimpFinisher extends AbstractFinisher
{
    protected function executeInternal()
    {
        /** @var FormDto */
        $form = GeneralUtility::makeInstance(FormDto::class);

        $form->setEmail($this->parseOption('email'));
        $form->setFirstName($this->parseOption('first_name'));
        $form->setLastName($this->parseOption('last_name'));

        $interests = $this->parseOption('interests');

        if (is_string($interests)) {
            $form->setInterest($interests);
        } else {
            $remapInterests = [];
            foreach ($interests as $interest) {
                $remapInterests[$interest] = true;
            }
            $form->setInterests($remapInterests);
        }

        return $this->handleRegistration($form);
    }

    private function getApiService(string $hash = null): ApiService
    {
        return GeneralUtility::makeInstance(ApiService::class, $hash);
    }

    protected function handleRegistration(FormDto $form = null)
    {
        /** @var StandaloneView */
        $view = GeneralUtility::makeInstance(StandaloneView::class);

        $view->setTemplate($this->options['templateName']);
        $view->getRequest()->setControllerExtensionName('mailchimp');
        $view->getTemplatePaths()->fillFromConfigurationArray($this->options);

        $listId = $this->parseOption('list_id');
        $doublOptIn = true;
        if ($this->parseOption('skip_double_optin') == 1) {
            $doublOptIn = false;
        }
        try {
            $apiService = $this->getApiService($this->parseOption('api_key') ?? '');
            $apiService->register($listId, $form, $doublOptIn);
        } catch (MemberExistsException $e) {
            $view->assign('error', 'memberExists');
        } catch (GeneralException $e) {
            $view->assign('error', 'general');
        }

        $view->assignMultiple([
            'form' => $form,
        ]);

        return $view->render();
    }

    public function getCategories()
    {
        return $this->getApiService($this->options['api_key'])->getCategories(
            $this->options['list_id'],
            $this->options['interest_id']
        );
    }
}
