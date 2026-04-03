<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Shubo_CompanyAccount::company_manage';

    private PageFactory $resultPageFactory;
    private CompanyRepositoryInterface $companyRepository;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CompanyRepositoryInterface $companyRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->companyRepository = $companyRepository;
    }

    public function execute(): ResultInterface
    {
        $companyId = (int) $this->getRequest()->getParam('entity_id');

        if ($companyId) {
            try {
                $company = $this->companyRepository->getById($companyId);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This company no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Shubo_CompanyAccount::company');
        $resultPage->getConfig()->getTitle()->prepend(
            $companyId ? __('Edit Company') : __('New Company')
        );

        return $resultPage;
    }
}
