<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api\Data;

/**
 * Company role data interface.
 *
 * @api
 */
interface CompanyRoleInterface
{
    public const ROLE_ID = 'role_id';
    public const COMPANY_ID = 'company_id';
    public const ROLE_NAME = 'role_name';
    public const DESCRIPTION = 'description';
    public const IS_DEFAULT = 'is_default';
    public const PERMISSIONS = 'permissions';

    /**
     * Get role ID.
     *
     * @return int|null
     */
    public function getRoleId(): ?int;

    /**
     * Set role ID.
     *
     * @param int $roleId
     * @return self
     */
    public function setRoleId(int $roleId): self;

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
     * Get role name.
     *
     * @return string
     */
    public function getRoleName(): string;

    /**
     * Set role name.
     *
     * @param string $roleName
     * @return self
     */
    public function setRoleName(string $roleName): self;

    /**
     * Get role description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set role description.
     *
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description): self;

    /**
     * Get is default flag.
     *
     * @return bool
     */
    public function getIsDefault(): bool;

    /**
     * Set is default flag.
     *
     * @param bool $isDefault
     * @return self
     */
    public function setIsDefault(bool $isDefault): self;

    /**
     * Get role permissions.
     *
     * @return string[]
     */
    public function getPermissions(): array;

    /**
     * Set role permissions.
     *
     * @param string[] $permissions
     * @return self
     */
    public function setPermissions(array $permissions): self;
}
