<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class CompanyRoles implements ArgumentInterface
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyRoleRepositoryInterface $roleRepository;
    private CompanyPermission $companyPermission;

    public function __construct(
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyRoleRepositoryInterface $roleRepository,
        CompanyPermission $companyPermission
    ) {
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->roleRepository = $roleRepository;
        $this->companyPermission = $companyPermission;
    }

    public function getRoles(): array
    {
        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return [];
        }

        $roles = $this->roleRepository->getByCompanyId((int) $company->getEntityId());
        $result = [];

        foreach ($roles as $role) {
            $permissions = $this->companyPermission->getRolePermissions((int) $role->getRoleId());
            $result[] = [
                'role_id' => $role->getRoleId(),
                'role_name' => $role->getRoleName(),
                'description' => $role->getDescription(),
                'is_default' => $role->getIsDefault(),
                'permissions' => array_keys(array_filter($permissions)),
            ];
        }

        return $result;
    }

    public function getAvailablePermissions(): array
    {
        return [
            'Shubo_CompanyAccount::view_dashboard' => __('View Dashboard'),
            'Shubo_CompanyAccount::manage_users' => __('Manage Users'),
            'Shubo_CompanyAccount::manage_roles' => __('Manage Roles'),
            'Shubo_CompanyAccount::manage_teams' => __('Manage Teams'),
            'Shubo_CompanyAccount::edit_profile' => __('Edit Company Profile'),
            'Shubo_CompanyAccount::view_orders' => __('View Orders'),
            'Shubo_CompanyAccount::manage_quotes' => __('Manage Quotes'),
            'Shubo_CompanyAccount::manage_purchase_orders' => __('Manage Purchase Orders'),
            'Shubo_CompanyAccount::view_credit' => __('View Credit'),
        ];
    }
}
