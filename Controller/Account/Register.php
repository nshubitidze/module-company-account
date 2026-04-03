<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Register implements HttpGetActionInterface
{
    private PageFactory $resultPageFactory;
    private RedirectFactory $redirectFactory;
    private CustomerSession $customerSession;
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        PageFactory $resultPageFactory,
        RedirectFactory $redirectFactory,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(): ResultInterface
    {
        if (!$this->scopeConfig->isSetFlag('shubo_company/general/registration_enabled', ScopeInterface::SCOPE_STORE)) {
            return $this->redirectFactory->create()->setPath('/');
        }

        if ($this->customerSession->isLoggedIn()) {
            return $this->redirectFactory->create()->setPath('company/account/dashboard');
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Register Your Company'));

        return $resultPage;
    }
}
