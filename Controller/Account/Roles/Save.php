<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Roles;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyRoleInterfaceFactory;

class Save extends AbstractAccount
{
    private CustomerSession $customerSession;
    private FormKeyValidator $formKeyValidator;
    private CompanyManagementInterface $companyManagement;
    private CompanyRoleRepositoryInterface $roleRepository;
    private CompanyRoleInterfaceFactory $roleFactory;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        FormKeyValidator $formKeyValidator,
        CompanyManagementInterface $companyManagement,
        CompanyRoleRepositoryInterface $roleRepository,
        CompanyRoleInterfaceFactory $roleFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->companyManagement = $companyManagement;
        $this->roleRepository = $roleRepository;
        $this->roleFactory = $roleFactory;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid form key.'));
            return $resultRedirect->setPath('company/account/roles');
        }

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return $resultRedirect->setPath('customer/account');
        }

        $request = $this->getRequest();
        $roleId = $request->getParam('role_id') ? (int) $request->getParam('role_id') : null;

        try {
            if ($roleId) {
                $role = $this->roleRepository->getById($roleId);
                if ((int) $role->getCompanyId() !== (int) $company->getEntityId()) {
                    throw new LocalizedException(__('This role does not belong to your company.'));
                }
            } else {
                $role = $this->roleFactory->create();
                $role->setCompanyId((int) $company->getEntityId());
            }

            $role->setRoleName(trim((string) $request->getParam('role_name')));
            $role->setDescription($request->getParam('description') ?: null);

            $this->roleRepository->save($role);
            $this->messageManager->addSuccessMessage(__('Role has been saved.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('company/account/roles');
    }
}
