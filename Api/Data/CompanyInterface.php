<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api\Data;

/**
 * Company entity data interface.
 *
 * @api
 */
interface CompanyInterface
{
    public const ENTITY_ID = 'entity_id';
    public const COMPANY_NAME = 'company_name';
    public const LEGAL_NAME = 'legal_name';
    public const COMPANY_EMAIL = 'company_email';
    public const VAT_TAX_ID = 'vat_tax_id';
    public const RESELLER_ID = 'reseller_id';
    public const PHONE = 'phone';
    public const WEBSITE = 'website';
    public const COMMENT = 'comment';
    public const STATUS = 'status';
    public const REJECT_REASON = 'reject_reason';
    public const ADMIN_CUSTOMER_ID = 'admin_customer_id';
    public const SALES_REPRESENTATIVE_ID = 'sales_representative_id';
    public const PARENT_ID = 'parent_id';
    public const STORE_ID = 'store_id';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /** Status constants */
    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_BLOCKED = 3;

    /**
     * Get company ID.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Set company ID.
     *
     * @param int $entityId
     * @return self
     */
    public function setEntityId(int $entityId): self;

    /**
     * Get company name.
     *
     * @return string
     */
    public function getCompanyName(): string;

    /**
     * Set company name.
     *
     * @param string $name
     * @return self
     */
    public function setCompanyName(string $name): self;

    /**
     * Get legal name.
     *
     * @return string|null
     */
    public function getLegalName(): ?string;

    /**
     * Set legal name.
     *
     * @param string|null $legalName
     * @return self
     */
    public function setLegalName(?string $legalName): self;

    /**
     * Get company email.
     *
     * @return string
     */
    public function getCompanyEmail(): string;

    /**
     * Set company email.
     *
     * @param string $email
     * @return self
     */
    public function setCompanyEmail(string $email): self;

    /**
     * Get VAT/Tax ID.
     *
     * @return string|null
     */
    public function getVatTaxId(): ?string;

    /**
     * Set VAT/Tax ID.
     *
     * @param string|null $vatTaxId
     * @return self
     */
    public function setVatTaxId(?string $vatTaxId): self;

    /**
     * Get reseller ID.
     *
     * @return string|null
     */
    public function getResellerId(): ?string;

    /**
     * Set reseller ID.
     *
     * @param string|null $resellerId
     * @return self
     */
    public function setResellerId(?string $resellerId): self;

    /**
     * Get phone number.
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * Set phone number.
     *
     * @param string|null $phone
     * @return self
     */
    public function setPhone(?string $phone): self;

    /**
     * Get website URL.
     *
     * @return string|null
     */
    public function getWebsite(): ?string;

    /**
     * Set website URL.
     *
     * @param string|null $website
     * @return self
     */
    public function setWebsite(?string $website): self;

    /**
     * Get admin comment.
     *
     * @return string|null
     */
    public function getComment(): ?string;

    /**
     * Set admin comment.
     *
     * @param string|null $comment
     * @return self
     */
    public function setComment(?string $comment): self;

    /**
     * Get company status.
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set company status.
     *
     * @param int $status
     * @return self
     */
    public function setStatus(int $status): self;

    /**
     * Get rejection reason.
     *
     * @return string|null
     */
    public function getRejectReason(): ?string;

    /**
     * Set rejection reason.
     *
     * @param string|null $reason
     * @return self
     */
    public function setRejectReason(?string $reason): self;

    /**
     * Get admin customer ID.
     *
     * @return int|null
     */
    public function getAdminCustomerId(): ?int;

    /**
     * Set admin customer ID.
     *
     * @param int|null $customerId
     * @return self
     */
    public function setAdminCustomerId(?int $customerId): self;

    /**
     * Get sales representative admin user ID.
     *
     * @return int|null
     */
    public function getSalesRepresentativeId(): ?int;

    /**
     * Set sales representative admin user ID.
     *
     * @param int|null $userId
     * @return self
     */
    public function setSalesRepresentativeId(?int $userId): self;

    /**
     * Get parent company ID.
     *
     * @return int|null
     */
    public function getParentId(): ?int;

    /**
     * Set parent company ID.
     *
     * @param int|null $parentId
     * @return self
     */
    public function setParentId(?int $parentId): self;

    /**
     * Get store ID.
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set store ID.
     *
     * @param int $storeId
     * @return self
     */
    public function setStoreId(int $storeId): self;

    /**
     * Get created at timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Get updated at timestamp.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;
}
