<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Shubo\CompanyAccount\Model\ResourceModel\Company as CompanyResource;
use Shubo\CompanyAccount\Model\ResourceModel\Company\CollectionFactory;

/**
 * Company repository implementation.
 */
class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * @var CompanyResource
     */
    private CompanyResource $resource;

    /**
     * @var CompanyFactory
     */
    private CompanyFactory $companyFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private SearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @param CompanyResource $resource
     * @param CompanyFactory $companyFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        CompanyResource $resource,
        CompanyFactory $companyFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->companyFactory = $companyFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $companyId): CompanyInterface
    {
        $company = $this->companyFactory->create();
        $this->resource->load($company, $companyId);

        if (!$company->getEntityId()) {
            throw new NoSuchEntityException(
                __('Company with ID "%1" does not exist.', $companyId)
            );
        }

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function getByEmail(string $email): CompanyInterface
    {
        $company = $this->companyFactory->create();
        $this->resource->load($company, $email, CompanyInterface::COMPANY_EMAIL);

        if (!$company->getEntityId()) {
            throw new NoSuchEntityException(
                __('Company with email "%1" does not exist.', $email)
            );
        }

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function getByCustomerId(int $customerId): CompanyInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->getSelect()
            ->join(
                ['cu' => $collection->getTable('shubo_company_user')],
                'main_table.entity_id = cu.company_id',
                []
            )
            ->where('cu.customer_id = ?', $customerId);

        $company = $collection->getFirstItem();

        if (!$company->getEntityId()) {
            throw new NoSuchEntityException(
                __('No company found for customer ID "%1".', $customerId)
            );
        }

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function save(CompanyInterface $company): CompanyInterface
    {
        try {
            /** @var Company $company */
            $this->resource->save($company);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not save company: %1', $e->getMessage()),
                $e
            );
        }

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function delete(CompanyInterface $company): bool
    {
        try {
            /** @var Company $company */
            $this->resource->delete($company);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete company: %1', $e->getMessage()),
                $e
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $companyId): bool
    {
        return $this->delete($this->getById($companyId));
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
