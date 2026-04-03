<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterfaceFactory;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Shubo_CompanyAccount::company_manage';

    private CompanyRepositoryInterface $companyRepository;
    private CompanyInterfaceFactory $companyFactory;

    public function __construct(
        Context $context,
        CompanyRepositoryInterface $companyRepository,
        CompanyInterfaceFactory $companyFactory
    ) {
        parent::__construct($context);
        $this->companyRepository = $companyRepository;
        $this->companyFactory = $companyFactory;
    }

    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $companyId = (int) ($data['entity_id'] ?? 0);

            if ($companyId) {
                $company = $this->companyRepository->getById($companyId);
            } else {
                $company = $this->companyFactory->create();
            }

            $company->setCompanyName($data['company_name'] ?? '');
            $company->setCompanyEmail($data['company_email'] ?? '');
            $company->setLegalName($data['legal_name'] ?? null);
            $company->setVatTaxId($data['vat_tax_id'] ?? null);
            $company->setResellerId($data['reseller_id'] ?? null);
            $company->setPhone($data['phone'] ?? null);
            $company->setWebsite($data['website'] ?? null);
            $company->setComment($data['comment'] ?? null);

            if (isset($data['status'])) {
                $company->setStatus((int) $data['status']);
            }

            $this->companyRepository->save($company);
            $this->messageManager->addSuccessMessage(__('The company has been saved.'));

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $company->getEntityId()]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the company.'));
        }

        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $data['entity_id'] ?? null]);
    }
}
