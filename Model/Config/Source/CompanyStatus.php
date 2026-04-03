<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CompanyStatus implements OptionSourceInterface
{
    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_BLOCKED = 3;

    public function toOptionArray(): array
    {
        return [
            ['value' => self::STATUS_PENDING, 'label' => __('Pending Approval')],
            ['value' => self::STATUS_APPROVED, 'label' => __('Approved')],
            ['value' => self::STATUS_REJECTED, 'label' => __('Rejected')],
            ['value' => self::STATUS_BLOCKED, 'label' => __('Blocked')],
        ];
    }
}
