<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel\CompanyTeam;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Shubo\CompanyAccount\Model\CompanyTeam;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyTeam as CompanyTeamResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(CompanyTeam::class, CompanyTeamResource::class);
    }
}
