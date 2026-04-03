<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Shubo_CompanyAccount::company_manage';

    private PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Shubo_CompanyAccount::company');
        $resultPage->getConfig()->getTitle()->prepend(__('B2B Companies'));

        return $resultPage;
    }
}
