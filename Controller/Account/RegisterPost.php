<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;

class RegisterPost implements HttpPostActionInterface
{
    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private FormKeyValidator $formKeyValidator;
    private CompanyManagementInterface $companyManagement;

    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        FormKeyValidator $formKeyValidator,
        CompanyManagementInterface $companyManagement
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->companyManagement = $companyManagement;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->redirectFactory->create();

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__('Invalid form key. Please try again.'));
            return $resultRedirect->setPath('*/*/register');
        }

        $companyData = [
            'company_name' => trim((string) $this->request->getParam('company_name')),
            'company_email' => trim((string) $this->request->getParam('company_email')),
            'legal_name' => $this->request->getParam('legal_name') ?: null,
            'vat_tax_id' => $this->request->getParam('vat_tax_id') ?: null,
            'phone' => $this->request->getParam('phone') ?: null,
            'website' => $this->request->getParam('website') ?: null,
            'street_line1' => trim((string) $this->request->getParam('street_line1')),
            'street_line2' => $this->request->getParam('street_line2') ?: null,
            'city' => trim((string) $this->request->getParam('city')),
            'region' => $this->request->getParam('region') ?: null,
            'region_id' => $this->request->getParam('region_id') ? (int) $this->request->getParam('region_id') : null,
            'postcode' => trim((string) $this->request->getParam('postcode')),
            'country_id' => trim((string) $this->request->getParam('country_id')),
        ];

        $adminData = [
            'firstname' => trim((string) $this->request->getParam('admin_firstname')),
            'lastname' => trim((string) $this->request->getParam('admin_lastname')),
            'email' => trim((string) $this->request->getParam('admin_email')),
            'password' => (string) $this->request->getParam('admin_password'),
        ];

        try {
            $this->companyManagement->register($companyData, $adminData);
            $this->messageManager->addSuccessMessage(
                __('Your company registration has been submitted and is pending approval. You will receive an email notification once it has been reviewed.')
            );
            return $resultRedirect->setPath('customer/account/login');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred during registration. Please try again.')
            );
        }

        return $resultRedirect->setPath('*/*/register');
    }
}
