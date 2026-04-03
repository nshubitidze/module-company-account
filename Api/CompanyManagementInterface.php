<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Shubo\CompanyAccount\Api\Data\CompanyUserInterface;

/**
 * High-level company management operations.
 *
 * @api
 */
interface CompanyManagementInterface
{
    /**
     * Register a new company with admin user.
     *
     * Creates company entity, customer account for admin, and company_user link.
     * Sets company status to Pending. Dispatches shubo_company_register_after event.
     *
     * @param mixed[] $companyData Company fields (company_name, company_email, etc.)
     * @param mixed[] $adminData Admin user fields (firstname, lastname, email, password)
     * @param mixed[] $addressData Company address fields
     * @return CompanyInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function register(array $companyData, array $adminData, array $addressData = []): CompanyInterface;

    /**
     * Approve a pending company.
     *
     * Sets status to Approved, sends approval email.
     * Dispatches shubo_company_approve_after event.
     *
     * @param int $companyId
     * @return CompanyInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function approve(int $companyId): CompanyInterface;

    /**
     * Reject a pending company.
     *
     * Sets status to Rejected with reason, sends rejection email.
     * Dispatches shubo_company_reject_after event.
     *
     * @param int $companyId
     * @param string $reason
     * @return CompanyInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function reject(int $companyId, string $reason): CompanyInterface;

    /**
     * Invite a user to the company.
     *
     * Creates customer account if not exists, creates company_user link.
     * Sends invitation email.
     *
     * @param int $companyId
     * @param mixed[] $userData User fields (firstname, lastname, email, job_title, phone)
     * @param int|null $roleId
     * @param int|null $teamId
     * @return CompanyUserInterface
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function inviteUser(int $companyId, array $userData, ?int $roleId = null, ?int $teamId = null): CompanyUserInterface;

    /**
     * Block an active company.
     *
     * @param int $companyId
     * @return CompanyInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function block(int $companyId): CompanyInterface;

    /**
     * Check if a customer belongs to any company.
     *
     * @param int $customerId
     * @return bool
     */
    public function isCompanyUser(int $customerId): bool;

    /**
     * Get company for a customer (via company_user table).
     *
     * @param int $customerId
     * @return CompanyInterface|null
     */
    public function getCompanyByCustomerId(int $customerId): ?CompanyInterface;
}
