<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Controller\Account\Teams;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyTeamRepositoryInterface;

class Delete extends AbstractAccount
{
    private CustomerSession $customerSession;
    private CompanyManagementInterface $companyManagement;
    private CompanyTeamRepositoryInterface $teamRepository;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CompanyManagementInterface $companyManagement,
        CompanyTeamRepositoryInterface $teamRepository
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->companyManagement = $companyManagement;
        $this->teamRepository = $teamRepository;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $teamId = (int) $this->getRequest()->getParam('team_id');

        $customerId = (int) $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company || !$teamId) {
            return $resultRedirect->setPath('company/account/teams');
        }

        try {
            $team = $this->teamRepository->getById($teamId);
            if ((int) $team->getCompanyId() !== (int) $company->getEntityId()) {
                throw new LocalizedException(__('This team does not belong to your company.'));
            }
            $this->teamRepository->delete($team);
            $this->messageManager->addSuccessMessage(__('Team has been deleted.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('company/account/teams');
    }
}
