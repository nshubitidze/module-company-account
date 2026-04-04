<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Shubo\CompanyAccount\Model\Config\Source\CompanyStatus;

class CompanyStatusChangeObserver implements ObserverInterface
{
    private TransportBuilder $transportBuilder;
    private ScopeConfigInterface $scopeConfig;
    private StoreManagerInterface $storeManager;

    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function execute(Observer $observer): void
    {
        /** @var CompanyInterface $company */
        $company = $observer->getEvent()->getData('company');
        $newStatus = (int) $observer->getEvent()->getData('new_status');

        if (!$company || !$company->getCompanyEmail()) {
            return;
        }

        $templateId = $this->getTemplateForStatus($newStatus);
        if (!$templateId) {
            return;
        }

        $storeId = $company->getStoreId();

        try {
            $store = $this->storeManager->getStore($storeId);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId,
                ])
                ->setTemplateVars([
                    'company_name' => $company->getCompanyName(),
                    'admin_name' => 'Company Admin',
                    'reject_reason' => $company->getRejectReason() ?? '',
                    'store_url' => $store->getBaseUrl(),
                ])
                ->setFromByScope('general', $storeId)
                ->addTo($company->getCompanyEmail())
                ->getTransport();

            $transport->sendMessage();
        } catch (\Exception $e) {
            // Log but don't break the flow if email fails
        }
    }

    private function getTemplateForStatus(int $status): ?string
    {
        return match ($status) {
            CompanyStatus::STATUS_APPROVED => $this->scopeConfig->getValue(
                'shubo_company/email/approved_template',
                ScopeInterface::SCOPE_STORE
            ) ?: 'shubo_company_approved',
            CompanyStatus::STATUS_REJECTED => $this->scopeConfig->getValue(
                'shubo_company/email/rejected_template',
                ScopeInterface::SCOPE_STORE
            ) ?: 'shubo_company_rejected',
            default => null,
        };
    }
}
