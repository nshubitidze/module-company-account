<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Model\AbstractModel;
use Shubo\CompanyAccount\Api\Data\CompanyAddressInterface;
use Shubo\CompanyAccount\Model\ResourceModel\CompanyAddress as CompanyAddressResource;

class CompanyAddress extends AbstractModel implements CompanyAddressInterface
{
    protected function _construct(): void
    {
        $this->_init(CompanyAddressResource::class);
    }

    public function getAddressId(): ?int
    {
        $id = $this->getData(self::ADDRESS_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setAddressId(int $addressId): self
    {
        return $this->setData(self::ADDRESS_ID, $addressId);
    }

    public function getCompanyId(): int
    {
        return (int) $this->getData(self::COMPANY_ID);
    }

    public function setCompanyId(int $companyId): self
    {
        return $this->setData(self::COMPANY_ID, $companyId);
    }

    public function getType(): string
    {
        return (string) $this->getData(self::TYPE);
    }

    public function setType(string $type): self
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getStreetLine1(): string
    {
        return (string) $this->getData(self::STREET_LINE1);
    }

    public function setStreetLine1(string $street): self
    {
        return $this->setData(self::STREET_LINE1, $street);
    }

    public function getStreetLine2(): ?string
    {
        return $this->getData(self::STREET_LINE2);
    }

    public function setStreetLine2(?string $street): self
    {
        return $this->setData(self::STREET_LINE2, $street);
    }

    public function getCity(): string
    {
        return (string) $this->getData(self::CITY);
    }

    public function setCity(string $city): self
    {
        return $this->setData(self::CITY, $city);
    }

    public function getRegion(): ?string
    {
        return $this->getData(self::REGION);
    }

    public function setRegion(?string $region): self
    {
        return $this->setData(self::REGION, $region);
    }

    public function getRegionId(): ?int
    {
        $id = $this->getData(self::REGION_ID);
        return $id !== null ? (int) $id : null;
    }

    public function setRegionId(?int $regionId): self
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    public function getPostcode(): string
    {
        return (string) $this->getData(self::POSTCODE);
    }

    public function setPostcode(string $postcode): self
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    public function getCountryId(): string
    {
        return (string) $this->getData(self::COUNTRY_ID);
    }

    public function setCountryId(string $countryId): self
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    public function getTelephone(): ?string
    {
        return $this->getData(self::TELEPHONE);
    }

    public function setTelephone(?string $telephone): self
    {
        return $this->setData(self::TELEPHONE, $telephone);
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
