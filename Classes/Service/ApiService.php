<?php

namespace Sup7even\Mailchimp\Service;

use DrewM\MailChimp\MailChimp;
use Sup7even\Mailchimp\Domain\Model\Dto\ExtensionConfiguration;
use Sup7even\Mailchimp\Domain\Model\Dto\FormDto;
use Sup7even\Mailchimp\Exception\GeneralException;
use Sup7even\Mailchimp\Exception\MemberExistsException;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ApiService
{
    /** @var MailChimp */
    protected $api;

    /** @var $logger Logger */
    protected $logger;

    /** @var string */
    protected $apiKey = '';

    public function __construct($usedApiKeyHash = null)
    {
        require_once(ExtensionManagementUtility::extPath('mailchimp', 'Resources/Private/Contrib/MailChimp/MailChimp.php'));

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->apiKey = $usedApiKeyHash ? $extensionConfiguration->getApiKeyByHash($usedApiKeyHash) : $extensionConfiguration->getFirstApiKey();
        $curlProxy = $extensionConfiguration->getProxy();
        $curlProxyPort = $extensionConfiguration->getProxyPort();

        $this->api = new MailChimp($this->apiKey, $curlProxy, $curlProxyPort);
        if ($extensionConfiguration->isForceIp4()) {
            $this->api->forceIpAddressv4();
        }
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get all lists
     *
     * @param int $maxCount max lists to be returned
     * @return array
     */
    public function getLists($maxCount = 50)
    {
        $groups = [];
        $list = $this->api->get('lists', ['count' => $maxCount]);

        if (is_array($list) && array_key_exists('lists', $list)) {
            foreach ($list['lists'] as $item) {
                $groups[$item['id']] = $item['name'];
            }
        }
        return $groups;
    }

    /**
     * @param string $list
     * @return array|false
     */
    public function getList(string $list)
    {
        return $this->api->get('lists/' . $list);
    }

    /**
     * Get all interest groups of a given list
     *
     * @param string $listId
     * @return array
     */
    public function getInterestLists(string $listId)
    {
        $groups = [];
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
    public function getCategories(string $listId, string $interestId)
    {
        $groupData = $this->api->get('lists/' . $listId . '/interest-categories/' . $interestId . '/');
        $result = [
            'title' => $groupData['title'],
            'type' => $groupData['type']
        ];

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
     * @param bool $doubleOptIn
     * @throws GeneralException
     * @throws MemberExistsException
     */
    public function register(string $listId, FormDto $form, bool $doubleOptIn = true)
    {
        $data = $this->getRegistrationData($listId, $form, $doubleOptIn);
        $response = $this->api->post("lists/$listId/members", $data);

        if ($response['status'] === 400 || $response['status'] === 401 || $response['status'] === 404) {
            $this->logger->error($response['status'] . ' ' . $response['detail']);
            $this->logger->error($response['detail'], (array)($response['errors'] ?? []));
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
     * @param bool $doubleOptIn
     * @return array
     */
    protected function getRegistrationData(string $listId, FormDto $form, bool $doubleOptIn)
    {
        $data = [
            'email_address' => $form->getEmail(),
            'status' => $doubleOptIn ? 'pending' : 'subscribed',
            'merge_fields' => [
                'FNAME' => (!empty($form->getFirstName())) ? $form->getFirstName() : '',
                'LNAME' => (!empty($form->getLastName())) ? $form->getLastName() : '',
            ]
        ];
        $interestData = $this->getInterests($form);
        if ($interestData) {
            $data['interests'] = $interestData;
        }

        if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData']) && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['mailchimp']['memberData'])) {
            $_params = [
                'data' => &$data,
                'listId' => $listId,
                'form' => $form
            ];
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
        $interestData = [];
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
