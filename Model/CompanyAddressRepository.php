<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyAddressRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyAddressInterface;
use Shubo\CompanyAccount\Api\Data\CompanySearchResultsInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyAddress as CompanyAddressResource;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyAddress\CollectionFactory;

class CompanyAddressRepository implements CompanyAddressRepositoryInterface
{
    private CompanyAddressResource $resource;
    private CompanyAddressFactory $addressFactory;
    private CollectionFactory $collectionFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        CompanyAddressResource $resource,
        CompanyAddressFactory $addressFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->addressFactory = $addressFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function getById(int $addressId): CompanyAddressInterface
    {
        $address = $this->addressFactory->create();
        $this->resource->load($address, $addressId);

        if (!$address->getAddressId()) {
            throw new NoSuchEntityException(__('Company address with ID "%1" does not exist.', $addressId));
        }

        return $address;
    }

    public function save(CompanyAddressInterface $address): CompanyAddressInterface
    {
        try {
            /** @var CompanyAddress $address */
            $this->resource->save($address);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save company address: %1', $e->getMessage()), $e);
        }

        return $address;
    }

    public function delete(CompanyAddressInterface $address): bool
    {
        try {
            /** @var CompanyAddress $address */
            $this->resource->delete($address);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete company address: %1', $e->getMessage()), $e);
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
