<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel\CompanyAddress;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Shubo\CompanyAccount\Model\CompanyAddress;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyAddress as CompanyAddressResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(CompanyAddress::class, CompanyAddressResource::class);
    }
}
