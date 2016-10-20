<?php

namespace Sup7even\Mailchimp\Service;

use DrewM\MailChimp\MailChimp;
use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ApiService
{
    /** @var MailChimp */
    protected $api;

    /** @var $logger Logger */
    protected $logger;

    public function __construct()
    {
        require_once(ExtensionManagementUtility::extPath('mailchimp', 'Resources/Private/Contrib/MailChimp/MailChimp.php'));

        /** @var \Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance('Sup7even\\Mailchimp\\Domain\\Model\\Dto\\ExtensionConfiguration');
        $apiKey = $extensionConfiguration->getApiKey();
        $curlProxy = $extensionConfiguration->getProxy();
        $curlProxyPort = $extensionConfiguration->getProxyPort();

        $this->api = new MailChimp($apiKey, $curlProxy, $curlProxyPort);
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
     * @param string $list
     * @return array|false
     */
    public function getList($list)
    {
        return $this->api->get('lists/' . $list);
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
        $groupData = $this->api->get('lists/' . $listId . '/interest-categories/' . $interestId . '/');
        $result = array(
            'title' => $groupData['title'],
            'type' => $groupData['type']
        );

        $list = $this->api->get('lists/' . $listId . '/interest-categories/' . $interestId . '/interests');
        if (isset($list['interests']) && is_array($list['interests'])) {
            foreach ($list['interests'] as $group) {
                $result['options'][$group['id']] = $group['name'];
            }
        }
        return $result;
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
        $data = $this->getRegistrationData($listId, $form);
        $response = $this->api->post("lists/$listId/members", $data);

        if ($response['status'] === 400 || $response['status'] === 401 || $response['status'] === 404) {
            $this->logger->error($response['status'] . ' ' . $response['detail']);
            $this->logger->error($response['detail'], (array)$response['errors']);
            if ($response['title'] === 'Member Exists') {
                $getResponse = $this->api->get("lists/$listId/members/" . $this->api->subscriberHash($data['email_address']));
                if ($getResponse['status'] !== 'subscribed') {
                    $this->api->put("lists/$listId/members/" . $this->api->subscriberHash($data['email_address']), $data);
                } else {
                  throw new MemberExistsException($response['detail']);
                }
            } else {
                throw new GeneralException($response['detail']);
            }
        }
    }

    /**
     * @param string $listId
     * @param FormDto $form
     * @return array
     */
    protected function getRegistrationData($listId, FormDto $form)
    {
        $data = array(
            'email_address' => $form->getEmail(),
            'status' => 'pending',
            'merge_fields' => array(
				        'FNAME' => (!empty($form->getFirstName())) ? $form->getFirstName() : '',
                'LNAME' => (!empty($form->getLastName())) ? $form->getLastName() : '',
            )
        );
        $interestData = $this->getInterests($form);
        if ($interestData) {
            $data['interests'] = $interestData;
        }

        if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData']) && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData'])) {
            $_params = array(
                'data' => &$data,
                'listId' => $listId,
                'form' => $form
            );
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData'] as $funcName) {
                GeneralUtility::callUserFunction($funcName, $_params, $this);
            }
        }
        return $data;
    }

    /**
     * @param FormDto $form
     * @return array
     */
    protected function getInterests(FormDto $form)
    {
        $interestData = array();
        // multi interests
        $interests = $form->getInterests();
        if ($interests) {
            foreach ($interests as $id => $state) {
                if ($state) {
                    $interestData[$id] = true;
                }
            }
        }
        // single interests
        $interest = $form->getInterest();
        if ($interest) {
            $interestData[$interest] = true;
        }
        return $interestData;
    }
}
