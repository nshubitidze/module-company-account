<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CompanyAddress extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('shubo_company_address', 'address_id');
    }
}
