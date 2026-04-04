<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Users;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;

class Delete extends AbstractAccount
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyUserRepositoryInterface $userRepository;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyUserRepositoryInterface $userRepository
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->userRepository = $userRepository;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $userId = (int) $this->getRequest()->getParam('user_id');

        if (!$userId) {
            $this->messageManager->addErrorMessage(__('Invalid user ID.'));
            return $resultRedirect->setPath('company/account/users');
        }

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return $resultRedirect->setPath('customer/account');
        }

        try {
            $user = $this->userRepository->getById($userId);
            if ((int) $user->getCompanyId() !== (int) $company->getEntityId()) {
                throw new LocalizedException(__('This user does not belong to your company.'));
            }
            if ($user->getIsCompanyAdmin()) {
                throw new LocalizedException(__('Cannot remove the company administrator.'));
            }

            $this->userRepository->delete($user);
            $this->messageManager->addSuccessMessage(__('User has been removed from the company.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('company/account/users');
    }
}
