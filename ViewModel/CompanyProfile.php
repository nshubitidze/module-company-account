<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shubo\CompanyAccount\Api\CompanyAddressRepositoryInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;

class CompanyProfile implements ArgumentInterface
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyAddressRepositoryInterface $addressRepository;

    public function __construct(
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyAddressRepositoryInterface $addressRepository
    ) {
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->addressRepository = $addressRepository;
    }

    public function getCompany(): ?CompanyInterface
    {
        $customerId = (int) $this->customerSession->getCustomerId();
        return $this->companyManagement->getCompanyByCustomerId($customerId);
    }

    public function getAddresses(): array
    {
        $company = $this->getCompany();
        if (!$company) {
            return [];
        }
        return $this->addressRepository->getByCompanyId((int) $company->getEntityId());
    }
}
