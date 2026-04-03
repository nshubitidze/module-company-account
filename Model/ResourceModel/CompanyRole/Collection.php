<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel\CompanyRole;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Shubo\CompanyAccount\Model\CompanyRole;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyRole as CompanyRoleResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(CompanyRole::class, CompanyRoleResource::class);
    }
}
