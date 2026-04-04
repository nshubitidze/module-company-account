<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Profile;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;

class Index extends AbstractAccount
{
    private PageFactory $resultPageFactory;
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
    }

    public function execute(): ResultInterface
    {
        $customerId = (int) $this->customerSession->getCustomerId();
        if (!$this->companyManagement->isCompanyUser($customerId)) {
            return $this->resultRedirectFactory->create()->setPath('customer/account');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Company Profile'));
        return $resultPage;
    }
}
