<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model;

use Magento\Framework\Model\AbstractModel;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Shubo\CompanyAccount\Model\ResourceModel\Company as CompanyResource;

/**
 * Company data model.
 */
class Company extends AbstractModel implements CompanyInterface
{
    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(CompanyResource::class);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId(): ?int
    {
        $id = $this->getData(self::ENTITY_ID);
        return $id !== null ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setEntityId($entityId): self
    {
        return $this->setData(self::ENTITY_ID, (int) $entityId);
    }

    /**
     * @inheritdoc
     */
    public function getCompanyName(): string
    {
        return (string) $this->getData(self::COMPANY_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setCompanyName(string $name): self
    {
        return $this->setData(self::COMPANY_NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function getLegalName(): ?string
    {
        return $this->getData(self::LEGAL_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setLegalName(?string $legalName): self
    {
        return $this->setData(self::LEGAL_NAME, $legalName);
    }

    /**
     * @inheritdoc
     */
    public function getCompanyEmail(): string
    {
        return (string) $this->getData(self::COMPANY_EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setCompanyEmail(string $email): self
    {
        return $this->setData(self::COMPANY_EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getVatTaxId(): ?string
    {
        return $this->getData(self::VAT_TAX_ID);
    }

    /**
     * @inheritdoc
     */
    public function setVatTaxId(?string $vatTaxId): self
    {
        return $this->setData(self::VAT_TAX_ID, $vatTaxId);
    }

    /**
     * @inheritdoc
     */
    public function getResellerId(): ?string
    {
        return $this->getData(self::RESELLER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setResellerId(?string $resellerId): self
    {
        return $this->setData(self::RESELLER_ID, $resellerId);
    }

    /**
     * @inheritdoc
     */
    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * @inheritdoc
     */
    public function setPhone(?string $phone): self
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritdoc
     */
    public function getWebsite(): ?string
    {
        return $this->getData(self::WEBSITE);
    }

    /**
     * @inheritdoc
     */
    public function setWebsite(?string $website): self
    {
        return $this->setData(self::WEBSITE, $website);
    }

    /**
     * @inheritdoc
     */
    public function getComment(): ?string
    {
        return $this->getData(self::COMMENT);
    }

    /**
     * @inheritdoc
     */
    public function setComment(?string $comment): self
    {
        return $this->setData(self::COMMENT, $comment);
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): int
    {
        return (int) $this->getData(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getRejectReason(): ?string
    {
        return $this->getData(self::REJECT_REASON);
    }

    /**
     * @inheritdoc
     */
    public function setRejectReason(?string $reason): self
    {
        return $this->setData(self::REJECT_REASON, $reason);
    }

    /**
     * @inheritdoc
     */
    public function getAdminCustomerId(): ?int
    {
        $id = $this->getData(self::ADMIN_CUSTOMER_ID);
        return $id !== null ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setAdminCustomerId(?int $customerId): self
    {
        return $this->setData(self::ADMIN_CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritdoc
     */
    public function getSalesRepresentativeId(): ?int
    {
        $id = $this->getData(self::SALES_REPRESENTATIVE_ID);
        return $id !== null ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setSalesRepresentativeId(?int $userId): self
    {
        return $this->setData(self::SALES_REPRESENTATIVE_ID, $userId);
    }

    /**
     * @inheritdoc
     */
    public function getParentId(): ?int
    {
        $id = $this->getData(self::PARENT_ID);
        return $id !== null ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setParentId(?int $parentId): self
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId(int $storeId): self
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }
}
