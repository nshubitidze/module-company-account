<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Roles;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;

class Delete extends AbstractAccount
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyRoleRepositoryInterface $roleRepository;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyRoleRepositoryInterface $roleRepository
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->roleRepository = $roleRepository;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $roleId = (int) $this->getRequest()->getParam('role_id');

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company || !$roleId) {
            return $resultRedirect->setPath('company/account/roles');
        }

        try {
            $role = $this->roleRepository->getById($roleId);
            if ((int) $role->getCompanyId() !== (int) $company->getEntityId()) {
                throw new LocalizedException(__('This role does not belong to your company.'));
            }
            $this->roleRepository->delete($role);
            $this->messageManager->addSuccessMessage(__('Role has been deleted.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('company/account/roles');
    }
}
