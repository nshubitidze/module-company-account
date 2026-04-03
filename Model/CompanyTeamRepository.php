<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyTeamInterface;
use Shubo\CompanyAccount\Api\Data\CompanySearchResultsInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyTeam as CompanyTeamResource;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyTeam\CollectionFactory;

class CompanyTeamRepository implements CompanyTeamRepositoryInterface
{
    private CompanyTeamResource $resource;
    private CompanyTeamFactory $teamFactory;
    private CollectionFactory $collectionFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        CompanyTeamResource $resource,
        CompanyTeamFactory $teamFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->teamFactory = $teamFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function getById(int $teamId): CompanyTeamInterface
    {
        $team = $this->teamFactory->create();
        $this->resource->load($team, $teamId);

        if (!$team->getTeamId()) {
            throw new NoSuchEntityException(__('Company team with ID "%1" does not exist.', $teamId));
        }

        return $team;
    }

    public function save(CompanyTeamInterface $team): CompanyTeamInterface
    {
        try {
            /** @var CompanyTeam $team */
            $this->resource->save($team);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save company team: %1', $e->getMessage()), $e);
        }

        return $team;
    }

    public function delete(CompanyTeamInterface $team): bool
    {
        try {
            /** @var CompanyTeam $team */
            $this->resource->delete($team);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete company team: %1', $e->getMessage()), $e);
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
