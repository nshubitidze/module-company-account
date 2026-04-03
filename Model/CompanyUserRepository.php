<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyUserInterface;
use Shubo\CompanyAccount\Api\Data\CompanySearchResultsInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyUser as CompanyUserResource;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyUser\CollectionFactory;

class CompanyUserRepository implements CompanyUserRepositoryInterface
{
    private CompanyUserResource $resource;
    private CompanyUserFactory $userFactory;
    private CollectionFactory $collectionFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        CompanyUserResource $resource,
        CompanyUserFactory $userFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->userFactory = $userFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function getById(int $userId): CompanyUserInterface
    {
        $user = $this->userFactory->create();
        $this->resource->load($user, $userId);

        if (!$user->getUserId()) {
            throw new NoSuchEntityException(__('Company user with ID "%1" does not exist.', $userId));
        }

        return $user;
    }

    public function getByCustomerId(int $customerId): CompanyUserInterface
    {
        $user = $this->userFactory->create();
        $this->resource->load($user, $customerId, 'customer_id');

        if (!$user->getUserId()) {
            throw new NoSuchEntityException(
                __('No company user found for customer ID "%1".', $customerId)
            );
        }

        return $user;
    }

    public function save(CompanyUserInterface $user): CompanyUserInterface
    {
        try {
            /** @var CompanyUser $user */
            $this->resource->save($user);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save company user: %1', $e->getMessage()), $e);
        }

        return $user;
    }

    public function delete(CompanyUserInterface $user): bool
    {
        try {
            /** @var CompanyUser $user */
            $this->resource->delete($user);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete company user: %1', $e->getMessage()), $e);
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
