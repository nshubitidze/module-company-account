<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\Data\CompanyUserInterface;

/**
 * Company user CRUD repository interface.
 *
 * @api
 */
interface CompanyUserRepositoryInterface
{
    /**
     * Get company user by ID.
     *
     * @param int $userId
     * @return CompanyUserInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $userId): CompanyUserInterface;

    /**
     * Save company user.
     *
     * @param CompanyUserInterface $companyUser
     * @return CompanyUserInterface
     * @throws CouldNotSaveException
     */
    public function save(CompanyUserInterface $companyUser): CompanyUserInterface;

    /**
     * Delete company user.
     *
     * @param CompanyUserInterface $companyUser
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CompanyUserInterface $companyUser): bool;

    /**
     * Delete company user by ID.
     *
     * @param int $userId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $userId): bool;

    /**
     * Get list of company users matching search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Get all users for a company.
     *
     * @param int $companyId
     * @return CompanyUserInterface[]
     */
    public function getByCompanyId(int $companyId): array;

    /**
     * Get company user by Magento customer ID.
     *
     * @param int $customerId
     * @return CompanyUserInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerId(int $customerId): CompanyUserInterface;
}
