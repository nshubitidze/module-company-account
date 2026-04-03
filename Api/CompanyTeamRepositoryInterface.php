<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\Data\CompanyTeamInterface;

/**
 * Company team CRUD repository interface.
 *
 * @api
 */
interface CompanyTeamRepositoryInterface
{
    /**
     * Get team by ID.
     *
     * @param int $teamId
     * @return CompanyTeamInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $teamId): CompanyTeamInterface;

    /**
     * Save team.
     *
     * @param CompanyTeamInterface $team
     * @return CompanyTeamInterface
     * @throws CouldNotSaveException
     */
    public function save(CompanyTeamInterface $team): CompanyTeamInterface;

    /**
     * Delete team.
     *
     * @param CompanyTeamInterface $team
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CompanyTeamInterface $team): bool;

    /**
     * Delete team by ID.
     *
     * @param int $teamId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $teamId): bool;

    /**
     * Get list of teams matching search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Get all teams for a company.
     *
     * @param int $companyId
     * @return CompanyTeamInterface[]
     */
    public function getByCompanyId(int $companyId): array;
}
