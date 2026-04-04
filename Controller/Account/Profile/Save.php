<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Profile;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;

class Save extends AbstractAccount
{
    private CustomerSession $customerSession;
    private FormKeyValidator $formKeyValidator;
    private CompanyManagementInterface $companyManagement;
    private CompanyRepositoryInterface $companyRepository;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        FormKeyValidator $formKeyValidator,
        CompanyManagementInterface $companyManagement,
        CompanyRepositoryInterface $companyRepository
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->companyManagement = $companyManagement;
        $this->companyRepository = $companyRepository;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid form key.'));
            return $resultRedirect->setPath('company/account/profile');
        }

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return $resultRedirect->setPath('customer/account');
        }

        $request = $this->getRequest();

        try {
            $company->setCompanyName(trim((string) $request->getParam('company_name')));
            $company->setLegalName($request->getParam('legal_name') ?: null);
            $company->setVatTaxId($request->getParam('vat_tax_id') ?: null);
            $company->setPhone($request->getParam('phone') ?: null);
            $company->setWebsite($request->getParam('website') ?: null);

            $this->companyRepository->save($company);
            $this->messageManager->addSuccessMessage(__('Company profile has been updated.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('company/account/profile');
    }
}
