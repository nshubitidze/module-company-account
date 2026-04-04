<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\ViewModel;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;

class CompanyUsers implements ArgumentInterface
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyUserRepositoryInterface $userRepository;
    private CompanyRoleRepositoryInterface $roleRepository;
    private CompanyTeamRepositoryInterface $teamRepository;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyUserRepositoryInterface $userRepository,
        CompanyRoleRepositoryInterface $roleRepository,
        CompanyTeamRepositoryInterface $teamRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->teamRepository = $teamRepository;
        $this->customerRepository = $customerRepository;
    }

    public function getCompany(): ?CompanyInterface
    {
        $customerId = (int) $this->customerSession->getCustomerId();
        return $this->companyManagement->getCompanyByCustomerId($customerId);
    }

    public function getUsers(): array
    {
        $company = $this->getCompany();
        if (!$company) {
            return [];
        }

        $companyUsers = $this->userRepository->getByCompanyId((int) $company->getEntityId());
        $users = [];

        foreach ($companyUsers as $companyUser) {
            try {
                $customer = $this->customerRepository->getById($companyUser->getCustomerId());
                $users[] = [
                    'user_id' => $companyUser->getUserId(),
                    'firstname' => $customer->getFirstname(),
                    'lastname' => $customer->getLastname(),
                    'email' => $customer->getEmail(),
                    'job_title' => $companyUser->getJobTitle(),
                    'role_id' => $companyUser->getRoleId(),
                    'team_id' => $companyUser->getTeamId(),
                    'status' => $companyUser->getStatus(),
                    'is_admin' => $companyUser->getIsCompanyAdmin(),
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        return $users;
    }

    public function getRoles(): array
    {
        $company = $this->getCompany();
        if (!$company) {
            return [];
        }
        return $this->roleRepository->getByCompanyId((int) $company->getEntityId());
    }

    public function getTeams(): array
    {
        $company = $this->getCompany();
        if (!$company) {
            return [];
        }
        return $this->teamRepository->getByCompanyId((int) $company->getEntityId());
    }
}
