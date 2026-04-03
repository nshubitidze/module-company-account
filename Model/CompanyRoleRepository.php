<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyRoleRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyRoleInterface;
use Shubo\CompanyAccount\Api\Data\CompanySearchResultsInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyRole as CompanyRoleResource;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyRole\CollectionFactory;

class CompanyRoleRepository implements CompanyRoleRepositoryInterface
{
    private CompanyRoleResource $resource;
    private CompanyRoleFactory $roleFactory;
    private CollectionFactory $collectionFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        CompanyRoleResource $resource,
        CompanyRoleFactory $roleFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->roleFactory = $roleFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function getById(int $roleId): CompanyRoleInterface
    {
        $role = $this->roleFactory->create();
        $this->resource->load($role, $roleId);

        if (!$role->getRoleId()) {
            throw new NoSuchEntityException(__('Company role with ID "%1" does not exist.', $roleId));
        }

        return $role;
    }

    public function save(CompanyRoleInterface $role): CompanyRoleInterface
    {
        try {
            /** @var CompanyRole $role */
            $this->resource->save($role);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save company role: %1', $e->getMessage()), $e);
        }

        return $role;
    }

    public function delete(CompanyRoleInterface $role): bool
    {
        try {
            /** @var CompanyRole $role */
            $this->resource->delete($role);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete company role: %1', $e->getMessage()), $e);
        }

        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): CompanySearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function getByCompanyId(int $companyId): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('company_id', $companyId);

        return $collection->getItems();
    }
}
