<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel\CompanyUser;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Shubo\CompanyAccount\Model\CompanyUser;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyUser as CompanyUserResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(CompanyUser::class, CompanyUserResource::class);
    }
}
