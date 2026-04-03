<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;
use Shubo\CompanyAccount\Model\ResourceModel\Company\CollectionFactory;

class MassAction extends Action
{
    public const ADMIN_RESOURCE = 'Shubo_CompanyAccount::company_manage';

    private Filter $filter;
    private CollectionFactory $collectionFactory;
    private CompanyRepositoryInterface $companyRepository;
    private CompanyManagementInterface $companyManagement;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CompanyRepositoryInterface $companyRepository,
        CompanyManagementInterface $companyManagement
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->companyRepository = $companyRepository;
        $this->companyManagement = $companyManagement;
    }

    public function execute(): ResultInterface
    {
        $action = $this->getRequest()->getParam('action');
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $count = 0;

        foreach ($collection as $company) {
            try {
                $companyId = (int) $company->getEntityId();
                switch ($action) {
                    case 'approve':
                        $this->companyManagement->approve($companyId);
                        $count++;
                        break;
                    case 'reject':
                        $this->companyManagement->reject($companyId, 'Mass action rejection');
                        $count++;
                        break;
                    case 'delete':
                        $this->companyRepository->deleteById($companyId);
                        $count++;
                        break;
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(
                    __('Error processing company #%1: %2', $company->getEntityId(), $e->getMessage())
                );
            }
        }

        if ($count) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 company(ies) have been processed.', $count)
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
