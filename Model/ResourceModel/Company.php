<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Company resource model.
 */
class Company extends AbstractDb
{
    public const TABLE_NAME = 'shubo_company';
    public const ID_FIELD = 'entity_id';

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD);
    }
}
