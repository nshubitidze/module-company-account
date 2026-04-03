<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;

/**
 * Company CRUD repository interface.
 *
 * @api
 */
interface CompanyRepositoryInterface
{
    /**
     * Get company by ID.
     *
     * @param int $companyId
     * @return CompanyInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $companyId): CompanyInterface;

    /**
     * Get company by company email.
     *
     * @param string $email
     * @return CompanyInterface
     * @throws NoSuchEntityException
     */
    public function getByEmail(string $email): CompanyInterface;

    /**
     * Get company by admin customer ID.
     *
     * @param int $customerId
     * @return CompanyInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerId(int $customerId): CompanyInterface;

    /**
     * Save company.
     *
     * @param CompanyInterface $company
     * @return CompanyInterface
     * @throws CouldNotSaveException
     */
    public function save(CompanyInterface $company): CompanyInterface;

    /**
     * Delete company.
     *
     * @param CompanyInterface $company
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CompanyInterface $company): bool;

    /**
     * Delete company by ID.
     *
     * @param int $companyId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $companyId): bool;

    /**
     * Get list of companies matching search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
