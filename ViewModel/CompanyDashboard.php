<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CompanyDashboard implements ArgumentInterface
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyUserRepositoryInterface $companyUserRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private ?CompanyInterface $company = null;

    public function __construct(
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyUserRepositoryInterface $companyUserRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->companyUserRepository = $companyUserRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function getCompany(): ?CompanyInterface
    {
        if ($this->company === null) {
            $customerId = (int) $this->customerSession->getCustomerId();
            $this->company = $this->companyManagement->getCompanyByCustomerId($customerId);
        }

        return $this->company;
    }

    public function getUserCount(): int
    {
        $company = $this->getCompany();
        if (!$company) {
            return 0;
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('company_id', $company->getEntityId())
            ->create();

        return $this->companyUserRepository->getList($searchCriteria)->getTotalCount();
    }

    public function getStatusLabel(): string
    {
        $company = $this->getCompany();
        if (!$company) {
            return '';
        }

        $labels = [
            0 => __('Pending Approval'),
            1 => __('Active'),
            2 => __('Rejected'),
            3 => __('Blocked'),
        ];

        return (string) ($labels[$company->getStatus()] ?? __('Unknown'));
    }
}
