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
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class InviteUser implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyPermission $companyPermission;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyPermission $companyPermission
    ) {
        $this->companyManagement = $companyManagement;
        $this->companyPermission = $companyPermission;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customerId = (int) $context->getUserId();
        if (!$this->companyPermission->isAllowed($customerId, 'Shubo_CompanyAccount::manage_users')) {
            throw new GraphQlAuthorizationException(__('You do not have permission to invite users.'));
        }

        $input = $args['input'] ?? [];
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            throw new GraphQlInputException(__('You are not associated with any company.'));
        }

        try {
            $userData = [
                'email' => $input['email'] ?? '',
                'firstname' => $input['firstname'] ?? '',
                'lastname' => $input['lastname'] ?? '',
                'job_title' => $input['job_title'] ?? null,
            ];

            $companyUser = $this->companyManagement->inviteUser(
                (int) $company->getEntityId(),
                $userData,
                isset($input['role_id']) ? (int) $input['role_id'] : null,
                isset($input['team_id']) ? (int) $input['team_id'] : null
            );

            return [
                'user_id' => $companyUser->getUserId(),
                'customer_id' => $companyUser->getCustomerId(),
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'job_title' => $companyUser->getJobTitle(),
                'status' => $companyUser->getStatus(),
                'is_company_admin' => $companyUser->getIsCompanyAdmin(),
            ];
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}
