<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;

class CustomerLoginObserver implements ObserverInterface
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyUserRepositoryInterface $companyUserRepository;

    public function __construct(
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyUserRepositoryInterface $companyUserRepository
    ) {
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->companyUserRepository = $companyUserRepository;
    }

    public function execute(Observer $observer): void
    {
        $customer = $observer->getEvent()->getCustomer();
        if (!$customer) {
            return;
        }

        $customerId = (int) $customer->getId();

        try {
            $company = $this->companyManagement->getCompanyByCustomerId($customerId);
            if ($company) {
                $this->customerSession->setData('shubo_company_id', $company->getEntityId());
                $this->customerSession->setData('shubo_company_name', $company->getCompanyName());
                $this->customerSession->setData('shubo_company_status', $company->getStatus());

                $companyUser = $this->companyUserRepository->getByCustomerId($customerId);
                $this->customerSession->setData('shubo_is_company_admin', $companyUser->getIsCompanyAdmin());
                $this->customerSession->setData('shubo_company_role_id', $companyUser->getRoleId());
            }
        } catch (NoSuchEntityException $e) {
            // Customer is not part of any company — nothing to do
        }
    }
}
