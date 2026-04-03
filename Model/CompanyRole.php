<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Model\AbstractModel;
use Shubo\CompanyAccount\Api\Data\CompanyRoleInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyRole as CompanyRoleResource;

class CompanyRole extends AbstractModel implements CompanyRoleInterface
{
    protected function _construct(): void
    {
        $this->_init(CompanyRoleResource::class);
    }

    public function getRoleId(): ?int
    {
        $id = $this->getData(self::ROLE_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setRoleId(int $roleId): self
    {
        return $this->setData(self::ROLE_ID, $roleId);
    }

    public function getCompanyId(): int
    {
        return (int) $this->getData(self::COMPANY_ID);
    }

    public function setCompanyId(int $companyId): self
    {
        return $this->setData(self::COMPANY_ID, $companyId);
    }

    public function getRoleName(): string
    {
        return (string) $this->getData(self::ROLE_NAME);
    }

    public function setRoleName(string $roleName): self
    {
        return $this->setData(self::ROLE_NAME, $roleName);
    }

    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription(?string $description): self
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getIsDefault(): bool
    {
        return (bool) $this->getData(self::IS_DEFAULT);
    }

    public function setIsDefault(bool $isDefault): self
    {
        return $this->setData(self::IS_DEFAULT, $isDefault);
    }
}
