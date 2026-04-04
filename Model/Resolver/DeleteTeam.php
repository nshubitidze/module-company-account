<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class DeleteTeam implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyTeamRepositoryInterface $teamRepository;
    private CompanyPermission $companyPermission;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyTeamRepositoryInterface $teamRepository,
        CompanyPermission $companyPermission
    ) {
        $this->companyManagement = $companyManagement;
        $this->teamRepository = $teamRepository;
        $this->companyPermission = $companyPermission;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customerId = (int) $context->getUserId();
        if (!$this->companyPermission->isAllowed($customerId, 'Shubo_CompanyAccount::manage_teams')) {
            throw new GraphQlAuthorizationException(__('You do not have permission to manage teams.'));
        }

        $teamId = (int) ($args['teamId'] ?? 0);
        if (!$teamId) {
            throw new GraphQlInputException(__('Team ID is required.'));
        }

        $team = $this->teamRepository->getById($teamId);
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company || (int) $team->getCompanyId() !== (int) $company->getEntityId()) {
            throw new GraphQlAuthorizationException(__('This team does not belong to your company.'));
        }

        $this->teamRepository->delete($team);
        return true;
    }
}
