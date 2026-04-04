<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Cron;

use Psr\Log\LoggerInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyUser\CollectionFactory;

class CleanupExpiredInvitations
{
    private CollectionFactory $collectionFactory;
    private LoggerInterface $logger;

    public function __construct(
        CollectionFactory $collectionFactory,
        LoggerInterface $logger
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
    }

    /**
     * Clean up company user records with inactive status older than 30 days.
     */
    public function execute(): void
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 0); // Inactive
        $collection->addFieldToFilter(
            'created_at',
            ['lt' => date('Y-m-d H:i:s', strtotime('-30 days'))]
        );

        $count = $collection->getSize();
        if ($count === 0) {
            return;
        }

        foreach ($collection as $user) {
            $user->delete();
        }

        $this->logger->info(sprintf(
            'Shubo CompanyAccount: Cleaned up %d expired invitations.',
            $count
        ));
    }
}
