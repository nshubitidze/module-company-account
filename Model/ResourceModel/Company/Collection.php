<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel\Company;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Shubo\CompanyAccount\Model\Company as CompanyModel;
use Shubo\CompanyAccount\Model\ResourceModel\Company as CompanyResource;

/**
 * Company collection.
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(CompanyModel::class, CompanyResource::class);
    }
}
