<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Plugin\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;

class AddCompanyDataToCustomerPlugin
{
    private CompanyManagementInterface $companyManagement;
    private CustomerExtensionFactory $customerExtensionFactory;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CustomerExtensionFactory $customerExtensionFactory
    ) {
        $this->companyManagement = $companyManagement;
        $this->customerExtensionFactory = $customerExtensionFactory;
    }

    public function afterGetById(
        CustomerRepositoryInterface $subject,
        CustomerInterface $customer
    ): CustomerInterface {
        return $this->addCompanyData($customer);
    }

    public function afterGet(
        CustomerRepositoryInterface $subject,
        CustomerInterface $customer
    ): CustomerInterface {
        return $this->addCompanyData($customer);
    }

    private function addCompanyData(CustomerInterface $customer): CustomerInterface
    {
        $extensionAttributes = $customer->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->customerExtensionFactory->create();
        }

        try {
            $customerId = (int) $customer->getId();
            if ($customerId && $this->companyManagement->isCompanyUser($customerId)) {
                $company = $this->companyManagement->getCompanyByCustomerId($customerId);
                if ($company) {
                    $extensionAttributes->setData('shubo_company_id', $company->getEntityId());
                    $extensionAttributes->setData('shubo_company_name', $company->getCompanyName());
                }
            }
        } catch (NoSuchEntityException $e) {
            // Not a company user
        }

        $customer->setExtensionAttributes($extensionAttributes);
        return $customer;
    }
}
