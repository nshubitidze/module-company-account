<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyRoleInterfaceFactory;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class SaveRole implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyRoleRepositoryInterface $roleRepository;
    private CompanyRoleInterfaceFactory $roleFactory;
    private CompanyPermission $companyPermission;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyRoleRepositoryInterface $roleRepository,
        CompanyRoleInterfaceFactory $roleFactory,
        CompanyPermission $companyPermission
    ) {
        $this->companyManagement = $companyManagement;
        $this->roleRepository = $roleRepository;
        $this->roleFactory = $roleFactory;
        $this->companyPermission = $companyPermission;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customerId = (int) $context->getUserId();
        if (!$this->companyPermission->isAllowed($customerId, 'Shubo_CompanyAccount::manage_roles')) {
            throw new GraphQlAuthorizationException(__('You do not have permission to manage roles.'));
        }

        $input = $args['input'] ?? [];
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            throw new GraphQlInputException(__('You are not associated with any company.'));
        }

        try {
            if (!empty($input['role_id'])) {
                $role = $this->roleRepository->getById((int) $input['role_id']);
                if ((int) $role->getCompanyId() !== (int) $company->getEntityId()) {
                    throw new GraphQlAuthorizationException(__('This role does not belong to your company.'));
                }
            } else {
                $role = $this->roleFactory->create();
                $role->setCompanyId((int) $company->getEntityId());
            }

            $role->setRoleName($input['role_name']);
            if (isset($input['description'])) {
                $role->setDescription($input['description']);
            }

            $this->roleRepository->save($role);

            // Save permissions
            if (isset($input['permissions'])) {
                $this->saveRolePermissions((int) $role->getRoleId(), $input['permissions']);
            }

            return [
                'role_id' => $role->getRoleId(),
                'role_name' => $role->getRoleName(),
                'description' => $role->getDescription(),
                'is_default' => $role->getIsDefault(),
                'permissions' => $input['permissions'] ?? [],
            ];
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }

    private function saveRolePermissions(int $roleId, array $permissions): void
    {
        $connection = $this->roleRepository->getById($roleId);
        // Permission saving is handled through the role_permission table
        // This would need a dedicated permission repository in a production implementation
    }
}
