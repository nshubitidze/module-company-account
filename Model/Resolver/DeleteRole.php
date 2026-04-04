<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class DeleteRole implements ResolverInterface
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
        if (!$this->companyPermission->isAllowed($customerId, 'Shubo_CompanyAccount::manage_roles')) {
            throw new GraphQlAuthorizationException(__('You do not have permission to manage roles.'));
        }

        $roleId = (int) ($args['roleId'] ?? 0);
        if (!$roleId) {
            throw new GraphQlInputException(__('Role ID is required.'));
        }

        $role = $this->roleRepository->getById($roleId);
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company || (int) $role->getCompanyId() !== (int) $company->getEntityId()) {
            throw new GraphQlAuthorizationException(__('This role does not belong to your company.'));
        }

        $this->roleRepository->delete($role);
        return true;
    }
}
