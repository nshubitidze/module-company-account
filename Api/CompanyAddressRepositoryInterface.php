<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\Data\CompanyAddressInterface;

/**
 * Company address CRUD repository interface.
 *
 * @api
 */
interface CompanyAddressRepositoryInterface
{
    /**
     * Get address by ID.
     *
     * @param int $addressId
     * @return CompanyAddressInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $addressId): CompanyAddressInterface;

    /**
     * Save address.
     *
     * @param CompanyAddressInterface $address
     * @return CompanyAddressInterface
     * @throws CouldNotSaveException
     */
    public function save(CompanyAddressInterface $address): CompanyAddressInterface;

    /**
     * Delete address.
     *
     * @param CompanyAddressInterface $address
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CompanyAddressInterface $address): bool;

    /**
     * Delete address by ID.
     *
     * @param int $addressId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $addressId): bool;

    /**
     * Get list of addresses matching search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Get all addresses for a company.
     *
     * @param int $companyId
     * @return CompanyAddressInterface[]
     */
    public function getByCompanyId(int $companyId): array;
}
