<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Authorization;

use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyRole\CollectionFactory as RolePermissionCollectionFactory;

class CompanyPermission
{
    private CompanyUserRepositoryInterface $companyUserRepository;
    private RolePermissionCollectionFactory $permissionCollectionFactory;
    private array $permissionCache = [];

    public function __construct(
        CompanyUserRepositoryInterface $companyUserRepository,
        RolePermissionCollectionFactory $permissionCollectionFactory
    ) {
        $this->companyUserRepository = $companyUserRepository;
        $this->permissionCollectionFactory = $permissionCollectionFactory;
    }

    /**
     * Check if a customer has a specific company-level permission.
     */
    public function isAllowed(int $customerId, string $resource): bool
    {
        try {
            $companyUser = $this->companyUserRepository->getByCustomerId($customerId);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        if (!$companyUser->getStatus()) {
            return false;
        }

        // Company admins have all permissions
        if ($companyUser->getIsCompanyAdmin()) {
            return true;
        }

        $roleId = $companyUser->getRoleId();
        if (!$roleId) {
            return false;
        }

        return $this->isRoleAllowed($roleId, $resource);
    }

    /**
     * Check if a role has a specific permission.
     */
    public function isRoleAllowed(int $roleId, string $resource): bool
    {
        $permissions = $this->getRolePermissions($roleId);
        return isset($permissions[$resource]) && $permissions[$resource];
    }

    /**
     * Get all permissions for a role (cached per request).
     */
    public function getRolePermissions(int $roleId): array
    {
        if (!isset($this->permissionCache[$roleId])) {
            $this->permissionCache[$roleId] = $this->loadRolePermissions($roleId);
        }

        return $this->permissionCache[$roleId];
    }

    /**
     * Get all permissions for a customer.
     */
    public function getCustomerPermissions(int $customerId): array
    {
        try {
            $companyUser = $this->companyUserRepository->getByCustomerId($customerId);
        } catch (NoSuchEntityException $e) {
            return [];
        }

        if ($companyUser->getIsCompanyAdmin()) {
            return $this->getAllPermissionResources();
        }

        $roleId = $companyUser->getRoleId();
        if (!$roleId) {
            return [];
        }

        $permissions = $this->getRolePermissions($roleId);
        return array_keys(array_filter($permissions));
    }

    private function loadRolePermissions(int $roleId): array
    {
        $connection = $this->permissionCollectionFactory->create()->getConnection();
        $tableName = $connection->getTableName('shubo_company_role_permission');

        $select = $connection->select()
            ->from($tableName, ['resource_id', 'is_allowed'])
            ->where('role_id = ?', $roleId);

        $result = [];
        foreach ($connection->fetchAll($select) as $row) {
            $result[$row['resource_id']] = (bool) $row['is_allowed'];
        }

        return $result;
    }

    private function getAllPermissionResources(): array
    {
        return [
            'Shubo_CompanyAccount::view_dashboard',
            'Shubo_CompanyAccount::manage_users',
            'Shubo_CompanyAccount::manage_roles',
            'Shubo_CompanyAccount::manage_teams',
            'Shubo_CompanyAccount::edit_profile',
            'Shubo_CompanyAccount::view_orders',
            'Shubo_CompanyAccount::manage_quotes',
            'Shubo_CompanyAccount::manage_purchase_orders',
            'Shubo_CompanyAccount::view_credit',
        ];
    }
}
