<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Teams;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyTeamInterfaceFactory;

class Save extends AbstractAccount
{
    private CustomerSession $customerSession;
    private FormKeyValidator $formKeyValidator;
    private CompanyManagementInterface $companyManagement;
    private CompanyTeamRepositoryInterface $teamRepository;
    private CompanyTeamInterfaceFactory $teamFactory;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        FormKeyValidator $formKeyValidator,
        CompanyManagementInterface $companyManagement,
        CompanyTeamRepositoryInterface $teamRepository,
        CompanyTeamInterfaceFactory $teamFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->companyManagement = $companyManagement;
        $this->teamRepository = $teamRepository;
        $this->teamFactory = $teamFactory;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid form key.'));
            return $resultRedirect->setPath('company/account/teams');
        }

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return $resultRedirect->setPath('customer/account');
        }

        $request = $this->getRequest();
        $teamId = $request->getParam('team_id') ? (int) $request->getParam('team_id') : null;

        try {
            if ($teamId) {
                $team = $this->teamRepository->getById($teamId);
                if ((int) $team->getCompanyId() !== (int) $company->getEntityId()) {
                    throw new LocalizedException(__('This team does not belong to your company.'));
                }
            } else {
                $team = $this->teamFactory->create();
                $team->setCompanyId((int) $company->getEntityId());
            }

            $team->setName(trim((string) $request->getParam('name')));
            $team->setDescription($request->getParam('description') ?: null);

            $parentTeamId = $request->getParam('parent_team_id');
            $team->setParentTeamId($parentTeamId ? (int) $parentTeamId : null);

            $this->teamRepository->save($team);
            $this->messageManager->addSuccessMessage(__('Team has been saved.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('company/account/teams');
    }
}
