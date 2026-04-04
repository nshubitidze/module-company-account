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
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyTeamInterfaceFactory;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class SaveTeam implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyTeamRepositoryInterface $teamRepository;
    private CompanyTeamInterfaceFactory $teamFactory;
    private CompanyPermission $companyPermission;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyTeamRepositoryInterface $teamRepository,
        CompanyTeamInterfaceFactory $teamFactory,
        CompanyPermission $companyPermission
    ) {
        $this->companyManagement = $companyManagement;
        $this->teamRepository = $teamRepository;
        $this->teamFactory = $teamFactory;
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

        $input = $args['input'] ?? [];
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            throw new GraphQlInputException(__('You are not associated with any company.'));
        }

        try {
            if (!empty($input['team_id'])) {
                $team = $this->teamRepository->getById((int) $input['team_id']);
                if ((int) $team->getCompanyId() !== (int) $company->getEntityId()) {
                    throw new GraphQlAuthorizationException(__('This team does not belong to your company.'));
                }
            } else {
                $team = $this->teamFactory->create();
                $team->setCompanyId((int) $company->getEntityId());
            }

            $team->setName($input['name']);
            if (isset($input['description'])) {
                $team->setDescription($input['description']);
            }
            if (isset($input['parent_team_id'])) {
                $team->setParentTeamId((int) $input['parent_team_id']);
            }

            $this->teamRepository->save($team);

            return [
                'team_id' => $team->getTeamId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
                'parent_team_id' => $team->getParentTeamId(),
            ];
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}
