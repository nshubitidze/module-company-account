<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Model\AbstractModel;
use Shubo\CompanyAccount\Api\Data\CompanyTeamInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyTeam as CompanyTeamResource;

class CompanyTeam extends AbstractModel implements CompanyTeamInterface
{
    protected function _construct(): void
    {
        $this->_init(CompanyTeamResource::class);
    }

    public function getTeamId(): ?int
    {
        $id = $this->getData(self::TEAM_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setTeamId(int $teamId): self
    {
        return $this->setData(self::TEAM_ID, $teamId);
    }

    public function getCompanyId(): int
    {
        return (int) $this->getData(self::COMPANY_ID);
    }

    public function setCompanyId(int $companyId): self
    {
        return $this->setData(self::COMPANY_ID, $companyId);
    }

    public function getName(): string
    {
        return (string) $this->getData(self::NAME);
    }

    public function setName(string $name): self
    {
        return $this->setData(self::NAME, $name);
    }

    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription(?string $description): self
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getParentTeamId(): ?int
    {
        $id = $this->getData(self::PARENT_TEAM_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setParentTeamId(?int $parentTeamId): self
    {
        return $this->setData(self::PARENT_TEAM_ID, $parentTeamId);
    }
}
