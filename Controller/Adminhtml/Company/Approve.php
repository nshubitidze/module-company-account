<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;

class Approve extends Action
{
    public const ADMIN_RESOURCE = 'Shubo_CompanyAccount::company_approve';

    private CompanyManagementInterface $companyManagement;

    public function __construct(
        Context $context,
        CompanyManagementInterface $companyManagement
    ) {
        parent::__construct($context);
        $this->companyManagement = $companyManagement;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $companyId = (int) $this->getRequest()->getParam('entity_id');

        if (!$companyId) {
            $this->messageManager->addErrorMessage(__('Cannot find the company to approve.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->companyManagement->approve($companyId);
            $this->messageManager->addSuccessMessage(__('The company has been approved.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $companyId]);
    }
}
