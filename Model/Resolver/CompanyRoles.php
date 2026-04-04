<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class CompanyRoles implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyRoleRepositoryInterface $roleRepository;
    private CompanyPermission $companyPermission;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyRoleRepositoryInterface $roleRepository,
        CompanyPermission $companyPermission
    ) {
        $this->companyManagement = $companyManagement;
        $this->roleRepository = $roleRepository;
        $this->companyPermission = $companyPermission;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customerId = (int) $context->getUserId();
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
}
