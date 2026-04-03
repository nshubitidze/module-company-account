<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\Data\CompanyRoleInterface;

/**
 * Company role CRUD repository interface.
 *
 * @api
 */
interface CompanyRoleRepositoryInterface
{
    /**
     * Get role by ID.
     *
     * @param int $roleId
     * @return CompanyRoleInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $roleId): CompanyRoleInterface;

    /**
     * Save role.
     *
     * @param CompanyRoleInterface $role
     * @return CompanyRoleInterface
     * @throws CouldNotSaveException
     */
    public function save(CompanyRoleInterface $role): CompanyRoleInterface;

    /**
     * Delete role.
     *
     * @param CompanyRoleInterface $role
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CompanyRoleInterface $role): bool;

    /**
     * Delete role by ID.
     *
     * @param int $roleId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $roleId): bool;

    /**
     * Get list of roles matching search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Get all roles for a company.
     *
     * @param int $companyId
     * @return CompanyRoleInterface[]
     */
    public function getByCompanyId(int $companyId): array;
}
