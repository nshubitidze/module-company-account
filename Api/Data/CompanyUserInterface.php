<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api\Data;

/**
 * Company user (customer-company link) data interface.
 *
 * @api
 */
interface CompanyUserInterface
{
    public const USER_ID = 'user_id';
    public const COMPANY_ID = 'company_id';
    public const CUSTOMER_ID = 'customer_id';
    public const ROLE_ID = 'role_id';
    public const TEAM_ID = 'team_id';
    public const JOB_TITLE = 'job_title';
    public const PHONE = 'phone';
    public const STATUS = 'status';
    public const IS_COMPANY_ADMIN = 'is_company_admin';

    /** User status constants */
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * Get user assignment ID.
     *
     * @return int|null
     */
    public function getUserId(): ?int;

    /**
     * Set user assignment ID.
     *
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self;

    /**
     * Get company ID.
     *
     * @return int
     */
    public function getCompanyId(): int;

    /**
     * Set company ID.
     *
     * @param int $companyId
     * @return self
     */
    public function setCompanyId(int $companyId): self;

    /**
     * Get Magento customer ID.
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Set Magento customer ID.
     *
     * @param int $customerId
     * @return self
     */
    public function setCustomerId(int $customerId): self;

    /**
     * Get assigned role ID.
     *
     * @return int|null
     */
    public function getRoleId(): ?int;

    /**
     * Set assigned role ID.
     *
     * @param int|null $roleId
     * @return self
     */
    public function setRoleId(?int $roleId): self;

    /**
     * Get assigned team ID.
     *
     * @return int|null
     */
    public function getTeamId(): ?int;

    /**
     * Set assigned team ID.
     *
     * @param int|null $teamId
     * @return self
     */
    public function setTeamId(?int $teamId): self;

    /**
     * Get job title.
     *
     * @return string|null
     */
    public function getJobTitle(): ?string;

    /**
     * Set job title.
     *
     * @param string|null $jobTitle
     * @return self
     */
    public function setJobTitle(?string $jobTitle): self;

    /**
     * Get direct phone number.
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * Set direct phone number.
     *
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone): self;

    /**
     * Get user status.
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set user status.
     *
     * @param int $status
     * @return self
     */
    public function setStatus(int $status): self;

    /**
     * Get is company admin flag.
     *
     * @return bool
     */
    public function getIsCompanyAdmin(): bool;

    /**
     * Set is company admin flag.
     *
     * @param bool $isCompanyAdmin
     * @return self
     */
    public function setIsCompanyAdmin(bool $isCompanyAdmin): self;
}
