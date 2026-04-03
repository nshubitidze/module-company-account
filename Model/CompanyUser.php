<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Model\AbstractModel;
use Shubo\CompanyAccount\Api\Data\CompanyUserInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyUser as CompanyUserResource;

class CompanyUser extends AbstractModel implements CompanyUserInterface
{
    protected function _construct(): void
    {
        $this->_init(CompanyUserResource::class);
    }

    public function getUserId(): ?int
    {
        $id = $this->getData(self::USER_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setUserId(int $userId): self
    {
        return $this->setData(self::USER_ID, $userId);
    }

    public function getCompanyId(): int
    {
        return (int) $this->getData(self::COMPANY_ID);
    }

    public function setCompanyId(int $companyId): self
    {
        return $this->setData(self::COMPANY_ID, $companyId);
    }

    public function getCustomerId(): int
    {
        return (int) $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId(int $customerId): self
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getRoleId(): ?int
    {
        $id = $this->getData(self::ROLE_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setRoleId(?int $roleId): self
    {
        return $this->setData(self::ROLE_ID, $roleId);
    }

    public function getTeamId(): ?int
    {
        $id = $this->getData(self::TEAM_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setTeamId(?int $teamId): self
    {
        return $this->setData(self::TEAM_ID, $teamId);
    }

    public function getJobTitle(): ?string
    {
        return $this->getData(self::JOB_TITLE);
    }

    public function setJobTitle(?string $jobTitle): self
    {
        return $this->setData(self::JOB_TITLE, $jobTitle);
    }

    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    public function setPhone(?string $phone): self
    {
        return $this->setData(self::PHONE, $phone);
    }

    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }

    public function setStatus(int $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getIsCompanyAdmin(): bool
    {
        return (bool) $this->getData(self::IS_COMPANY_ADMIN);
    }

    public function setIsCompanyAdmin(bool $isAdmin): self
    {
        return $this->setData(self::IS_COMPANY_ADMIN, $isAdmin);
    }
}
