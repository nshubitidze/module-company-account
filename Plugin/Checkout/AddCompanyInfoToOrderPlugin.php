<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Plugin\Checkout;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class AddCompanyInfoToOrderPlugin
{
    private CustomerSession $customerSession;
    private OrderExtensionFactory $orderExtensionFactory;

    public function __construct(
        CustomerSession $customerSession,
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * After order save, attach company_id if customer is part of a company.
     */
    public function afterSave(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ): OrderInterface {
        $companyId = $this->customerSession->getData('shubo_company_id');

        if ($companyId) {
            $extensionAttributes = $order->getExtensionAttributes();
            if (!$extensionAttributes) {
                $extensionAttributes = $this->orderExtensionFactory->create();
            }
            $extensionAttributes->setData('shubo_company_id', (int) $companyId);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $order;
    }

    /**
     * After order load, populate company_id extension attribute.
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ): OrderInterface {
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        // Company ID would be stored via a custom order attribute or sales_order column
        // For now, we set it from the session context
        $companyId = $this->customerSession->getData('shubo_company_id');
        if ($companyId) {
            $extensionAttributes->setData('shubo_company_id', (int) $companyId);
        }

        $order->setExtensionAttributes($extensionAttributes);
        return $order;
    }
}
