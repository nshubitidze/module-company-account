<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Math\Random;
use Magento\Store\Model\StoreManagerInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyRepositoryInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterfaceFactory;
use Shubo\CompanyAccount\Api\Data\CompanyUserInterface;
use Shubo\CompanyAccount\Api\Data\CompanyUserInterfaceFactory;

/**
 * High-level company management operations.
 *
 * Handles registration, approval/rejection workflow, and user invitations.
 */
class CompanyManagement implements CompanyManagementInterface
{
    /**
     * @var CompanyRepositoryInterface
     */
    private CompanyRepositoryInterface $companyRepository;

    /**
     * @var CompanyInterfaceFactory
     */
    private CompanyInterfaceFactory $companyFactory;

    /**
     * @var CompanyUserRepositoryInterface
     */
    private CompanyUserRepositoryInterface $companyUserRepository;

    /**
     * @var CompanyUserInterfaceFactory
     */
    private CompanyUserInterfaceFactory $companyUserFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var AccountManagementInterface
     */
    private AccountManagementInterface $accountManagement;

    /**
     * @var CustomerInterfaceFactory
     */
    private CustomerInterfaceFactory $customerFactory;

    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Random
     */
    private Random $mathRandom;

    /**
     * @param CompanyRepositoryInterface $companyRepository
     * @param CompanyInterfaceFactory $companyFactory
     * @param CompanyUserRepositoryInterface $companyUserRepository
     * @param CompanyUserInterfaceFactory $companyUserFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $accountManagement
     * @param CustomerInterfaceFactory $customerFactory
     * @param EventManager $eventManager
     * @param StoreManagerInterface $storeManager
     * @param Random $mathRandom
     */
    public function __construct(
        CompanyRepositoryInterface $companyRepository,
        CompanyInterfaceFactory $companyFactory,
        CompanyUserRepositoryInterface $companyUserRepository,
        CompanyUserInterfaceFactory $companyUserFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        CustomerInterfaceFactory $customerFactory,
        EventManager $eventManager,
        StoreManagerInterface $storeManager,
        Random $mathRandom
    ) {
        $this->companyRepository = $companyRepository;
        $this->companyFactory = $companyFactory;
        $this->companyUserRepository = $companyUserRepository;
        $this->companyUserFactory = $companyUserFactory;
        $this->customerRepository = $customerRepository;
        $this->accountManagement = $accountManagement;
        $this->customerFactory = $customerFactory;
        $this->eventManager = $eventManager;
        $this->storeManager = $storeManager;
        $this->mathRandom = $mathRandom;
    }

    /**
     * @inheritdoc
     */
    public function register(array $companyData, array $adminData, array $addressData = []): CompanyInterface
    {
        try {
            // Create the customer account for the company admin
            $customer = $this->customerFactory->create();
            $customer->setFirstname($adminData['firstname'] ?? '');
            $customer->setLastname($adminData['lastname'] ?? '');
            $customer->setEmail($adminData['email'] ?? '');
            $customer->setStoreId($this->storeManager->getStore()->getId());
            $customer->setWebsiteId($this->storeManager->getStore()->getWebsiteId());

            $password = $adminData['password'] ?? null;
            $customer = $this->accountManagement->createAccount($customer, $password);

            // Create the company entity
            /** @var CompanyInterface $company */
            $company = $this->companyFactory->create();
            $company->setCompanyName($companyData['company_name'] ?? '');
            $company->setCompanyEmail($companyData['company_email'] ?? '');

            if (isset($companyData['legal_name'])) {
                $company->setLegalName($companyData['legal_name']);
            }
            if (isset($companyData['vat_tax_id'])) {
                $company->setVatTaxId($companyData['vat_tax_id']);
            }
            if (isset($companyData['reseller_id'])) {
                $company->setResellerId($companyData['reseller_id']);
            }
            if (isset($companyData['phone'])) {
                $company->setPhone($companyData['phone']);
            }
            if (isset($companyData['website'])) {
                $company->setWebsite($companyData['website']);
            }

            $company->setStatus(CompanyInterface::STATUS_PENDING);
            $company->setAdminCustomerId((int) $customer->getId());
            $company->setStoreId((int) $this->storeManager->getStore()->getId());

            $company = $this->companyRepository->save($company);

            // Create the company_user link for the admin
            /** @var CompanyUserInterface $companyUser */
            $companyUser = $this->companyUserFactory->create();
            $companyUser->setCompanyId((int) $company->getEntityId());
            $companyUser->setCustomerId((int) $customer->getId());
            $companyUser->setStatus(CompanyUserInterface::STATUS_ACTIVE);
            $companyUser->setIsCompanyAdmin(true);

            $this->companyUserRepository->save($companyUser);

            $this->eventManager->dispatch('shubo_company_register_after', [
                'company' => $company,
                'admin_customer' => $customer,
            ]);

            return $company;
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Could not register company: %1', $e->getMessage()),
                $e
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function approve(int $companyId): CompanyInterface
    {
        $company = $this->companyRepository->getById($companyId);
        $oldStatus = $company->getStatus();

        $company->setStatus(CompanyInterface::STATUS_APPROVED);
        $company->setRejectReason(null);
        $company = $this->companyRepository->save($company);

        $this->eventManager->dispatch('shubo_company_approve_after', [
            'company' => $company,
        ]);

        $this->eventManager->dispatch('shubo_company_status_change', [
            'company' => $company,
            'old_status' => $oldStatus,
            'new_status' => CompanyInterface::STATUS_APPROVED,
        ]);

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function reject(int $companyId, string $reason): CompanyInterface
    {
        $company = $this->companyRepository->getById($companyId);
        $oldStatus = $company->getStatus();

        $company->setStatus(CompanyInterface::STATUS_REJECTED);
        $company->setRejectReason($reason);
        $company = $this->companyRepository->save($company);

        $this->eventManager->dispatch('shubo_company_reject_after', [
            'company' => $company,
            'reason' => $reason,
        ]);

        $this->eventManager->dispatch('shubo_company_status_change', [
            'company' => $company,
            'old_status' => $oldStatus,
            'new_status' => CompanyInterface::STATUS_REJECTED,
        ]);

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function inviteUser(
        int $companyId,
        array $userData,
        ?int $roleId = null,
        ?int $teamId = null
    ): CompanyUserInterface {
        // Verify company exists
        $company = $this->companyRepository->getById($companyId);

        $email = $userData['email'] ?? '';
        if (empty($email)) {
            throw new LocalizedException(__('Email is required to invite a user.'));
        }

        // Check if the customer already belongs to a company
        try {
            $existingUser = $this->companyUserRepository->getByCustomerId(
                (int) $this->customerRepository->get($email)->getId()
            );
            throw new LocalizedException(
                __('Customer with email "%1" already belongs to a company.', $email)
            );
        } catch (NoSuchEntityException $e) {
            // Customer does not exist or is not in a company — proceed
        }

        // Create or load the customer account
        try {
            $customer = $this->customerRepository->get($email);
        } catch (NoSuchEntityException $e) {
            $customer = $this->customerFactory->create();
            $customer->setFirstname($userData['firstname'] ?? '');
            $customer->setLastname($userData['lastname'] ?? '');
            $customer->setEmail($email);
            $customer->setStoreId((int) $this->storeManager->getStore()->getId());
            $customer->setWebsiteId((int) $this->storeManager->getStore()->getWebsiteId());

            // Generate a random password for the invited user
            $password = $this->mathRandom->getRandomString(16);
            $customer = $this->accountManagement->createAccount($customer, $password);
        }

        // Create the company_user link
        /** @var CompanyUserInterface $companyUser */
        $companyUser = $this->companyUserFactory->create();
        $companyUser->setCompanyId($companyId);
        $companyUser->setCustomerId((int) $customer->getId());
        $companyUser->setStatus(CompanyUserInterface::STATUS_ACTIVE);
        $companyUser->setIsCompanyAdmin(false);

        if ($roleId !== null) {
            $companyUser->setRoleId($roleId);
        }
        if ($teamId !== null) {
            $companyUser->setTeamId($teamId);
        }
        if (isset($userData['job_title'])) {
            $companyUser->setJobTitle($userData['job_title']);
        }
        if (isset($userData['phone'])) {
            $companyUser->setPhone($userData['phone']);
        }

        $companyUser = $this->companyUserRepository->save($companyUser);

        $this->eventManager->dispatch('shubo_company_user_add_after', [
            'company_user' => $companyUser,
            'company' => $company,
        ]);

        return $companyUser;
    }

    /**
     * @inheritdoc
     */
    public function block(int $companyId): CompanyInterface
    {
        $company = $this->companyRepository->getById($companyId);
        $oldStatus = $company->getStatus();

        $company->setStatus(CompanyInterface::STATUS_BLOCKED);
        $company = $this->companyRepository->save($company);

        $this->eventManager->dispatch('shubo_company_status_change', [
            'company' => $company,
            'old_status' => $oldStatus,
            'new_status' => CompanyInterface::STATUS_BLOCKED,
        ]);

        return $company;
    }

    /**
     * @inheritdoc
     */
    public function isCompanyUser(int $customerId): bool
    {
        try {
            $this->companyUserRepository->getByCustomerId($customerId);
            return true;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCompanyByCustomerId(int $customerId): ?CompanyInterface
    {
        try {
            return $this->companyRepository->getByCustomerId($customerId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
