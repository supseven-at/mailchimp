<?php

namespace Sup7\Mailchimp\Service;

use Sup7\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7\Mailchimp\Exception\GeneralException;
use Sup7\Mailchimp\Exception\MemberExistsException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Log\Logger;

class ApiService
{

    protected $api;

    /** @var $logger Logger */
    protected $logger;

    public function __construct()
    {
        require_once(ExtensionManagementUtility::extPath('mailchimp', 'Resources/Private/Contrib/MailChimp/MailChimp.php'));

        /** @var \Sup7\Mailchimp\Domain\Model\Dto\ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance('Sup7\\Mailchimp\\Domain\\Model\\Dto\\ExtensionConfiguration');
        $apiKey = $extensionConfiguration->getApiKey();

        $this->api = new \DrewM\MailChimp\MailChimp($apiKey);
        $this->logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
    }

    /**
     * Get all lists
     * 
     * @return array
     */
    public function getLists()
    {
        $groups = array();
        $list = $this->api->get('lists');

        foreach ($list['lists'] as $item) {
            $groups[$item['id']] = $item['name'];
        }
        return $groups;
    }

    /**
     * Get all interest groups of a given list
     * 
     * @param string $listId
     * @return array
     */
    public function getInterestLists($listId)
    {
        $groups = array();
        $list = $this->api->get('lists/' . $listId . '/interest-categories/');

        foreach ($list['categories'] as $group) {
            $groups[$group['id']] = $group['title'];
        }
        return $groups;
    }

    /**
     * Get all interest categories of a given list & interest
     * @param string $listId
     * @param string $interestId
     * @return array
     */
    public function getCategories($listId, $interestId)
    {
        $groups = array();
        $list = $this->api->get('lists/' . $listId . '/interest-categories/' . $interestId . '/interests');
        foreach ($list['interests'] as $group) {
            $groups[$group['id']] = $group['name'];
        }

        return $groups;
    }

    /**
     * Register a user
     *
     * @param string $listId
     * @param FormDto $form
     * @throws GeneralException
     * @throws MemberExistsException
     */
    public function register($listId, FormDto $form)
    {
        $data = array(
            'email_address' => $form->getEmail(),
            'status' => 'pending',
            'merge_fields' => array(
                'FNAME' => $form->getFirstName(),
                'LNAME' => $form->getLastName(),
            ),
            'intxerests' => array(),
        );
        $interests = $form->getInterests();
        if ($interests) {
            $interestData = array();
            foreach ($interests as $id => $state) {
                if ($state) {
                    $interestData[$id] = true;
                }
            }
            if ($interestData) {
                $data['interests'] = $interestData;
            }
        }

        $response = $this->api->post("lists/$listId/members", $data);

        if ($response['status'] === 400 || $response['status'] === 401 || $response['status'] === 404) {
            $this->logger->error($response['status']);
            $this->logger->error($response['detail'], (array)$response['errors']);
            if ($response['title'] === 'Member Exists') {
                throw new MemberExistsException($response['detail']);
            }

            throw new GeneralException($response['detail']);
        }
    }
}