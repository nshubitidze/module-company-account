<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Users;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;

class AddPost extends AbstractAccount
{
    private CustomerSession $customerSession;
    private FormKeyValidator $formKeyValidator;
    private CompanyManagementInterface $companyManagement;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        FormKeyValidator $formKeyValidator,
        CompanyManagementInterface $companyManagement
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->companyManagement = $companyManagement;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid form key.'));
            return $resultRedirect->setPath('company/account/users');
        }

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return $resultRedirect->setPath('customer/account');
        }

        $request = $this->getRequest();
        $userData = [
            'email' => trim((string) $request->getParam('email')),
            'firstname' => trim((string) $request->getParam('firstname')),
            'lastname' => trim((string) $request->getParam('lastname')),
            'job_title' => $request->getParam('job_title') ?: null,
        ];

        $roleId = $request->getParam('role_id') ? (int) $request->getParam('role_id') : null;
        $teamId = $request->getParam('team_id') ? (int) $request->getParam('team_id') : null;

        try {
            $this->companyManagement->inviteUser((int) $company->getEntityId(), $userData, $roleId, $teamId);
            $this->messageManager->addSuccessMessage(__('User has been invited successfully.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while inviting the user.'));
        }

        return $resultRedirect->setPath('company/account/users');
    }
}
