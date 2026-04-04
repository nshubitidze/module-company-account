<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;

class CompanyTeams implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyTeamRepositoryInterface $teamRepository;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyTeamRepositoryInterface $teamRepository
    ) {
        $this->companyManagement = $companyManagement;
        $this->teamRepository = $teamRepository;
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

        $teams = $this->teamRepository->getByCompanyId((int) $company->getEntityId());
        $result = [];

        foreach ($teams as $team) {
            $result[] = [
                'team_id' => $team->getTeamId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
                'parent_team_id' => $team->getParentTeamId(),
            ];
        }

        return $result;
    }
}
