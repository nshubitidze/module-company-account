<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Shubo_CompanyAccount::company_delete';

    private CompanyRepositoryInterface $companyRepository;

    public function __construct(
        Context $context,
        CompanyRepositoryInterface $companyRepository
    ) {
        parent::__construct($context);
        $this->companyRepository = $companyRepository;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $companyId = (int) $this->getRequest()->getParam('entity_id');

        if (!$companyId) {
            $this->messageManager->addErrorMessage(__('Cannot find the company to delete.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->companyRepository->deleteById($companyId);
            $this->messageManager->addSuccessMessage(__('The company has been deleted.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
