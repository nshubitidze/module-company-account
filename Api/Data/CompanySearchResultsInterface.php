<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Search results interface for company entities.
 *
 * @api
 */
interface CompanySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get company list.
     *
     * @return \Shubo\CompanyAccount\Api\Data\CompanyInterface[]
     */
    public function getItems(): array;

    /**
     * Set company list.
     *
     * @param \Shubo\CompanyAccount\Api\Data\CompanyInterface[] $items
     * @return self
     */
    public function setItems(array $items): self;
}
