<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api\Data;

/**
 * Company address data interface.
 *
 * @api
 */
interface CompanyAddressInterface
{
    public const ADDRESS_ID = 'address_id';
    public const COMPANY_ID = 'company_id';
    public const TYPE = 'type';
    public const STREET_LINE1 = 'street_line1';
    public const STREET_LINE2 = 'street_line2';
    public const CITY = 'city';
    public const REGION = 'region';
    public const REGION_ID = 'region_id';
    public const POSTCODE = 'postcode';
    public const COUNTRY_ID = 'country_id';
    public const TELEPHONE = 'telephone';
    public const IS_DEFAULT = 'is_default';

    /**
     * Get address ID.
     *
     * @return int|null
     */
    public function getAddressId(): ?int;

    /**
     * Set address ID.
     *
     * @param int $addressId
     * @return self
     */
    public function setAddressId(int $addressId): self;

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
     * Get address type (billing, shipping, headquarters).
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Set address type.
     *
     * @param string $type
     * @return self
     */
    public function setType(string $type): self;

    /**
     * Get street line 1.
     *
     * @return string
     */
    public function getStreetLine1(): string;

    /**
     * Set street line 1.
     *
     * @param string $streetLine1
     * @return self
     */
    public function setStreetLine1(string $streetLine1): self;

    /**
     * Get street line 2.
     *
     * @return string|null
     */
    public function getStreetLine2(): ?string;

    /**
     * Set street line 2.
     *
     * @param string|null $streetLine2
     * @return self
     */
    public function setStreetLine2(?string $streetLine2): self;

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity(): string;

    /**
     * Set city.
     *
     * @param string $city
     * @return self
     */
    public function setCity(string $city): self;

    /**
     * Get region/state name.
     *
     * @return string|null
     */
    public function getRegion(): ?string;

    /**
     * Set region/state name.
     *
     * @param string|null $region
     * @return self
     */
    public function setRegion(?string $region): self;

    /**
     * Get region ID.
     *
     * @return int|null
     */
    public function getRegionId(): ?int;

    /**
     * Set region ID.
     *
     * @param int|null $regionId
     * @return self
     */
    public function setRegionId(?int $regionId): self;

    /**
     * Get postal code.
     *
     * @return string
     */
    public function getPostcode(): string;

    /**
     * Set postal code.
     *
     * @param string $postcode
     * @return self
     */
    public function setPostcode(string $postcode): self;

    /**
     * Get country ISO code.
     *
     * @return string
     */
    public function getCountryId(): string;

    /**
     * Set country ISO code.
     *
     * @param string $countryId
     * @return self
     */
    public function setCountryId(string $countryId): self;

    /**
     * Get telephone.
     *
     * @return string|null
     */
    public function getTelephone(): ?string;

    /**
     * Set telephone.
     *
     * @param string|null $telephone
     * @return self
     */
    public function setTelephone(?string $telephone): self;

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
}
