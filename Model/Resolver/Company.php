<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyAddressRepositoryInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;
use Shubo\CompanyAccount\Model\Config\Source\CompanyStatus;

class Company implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyAddressRepositoryInterface $addressRepository;
    private CompanyUserRepositoryInterface $userRepository;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyAddressRepositoryInterface $addressRepository,
        CompanyUserRepositoryInterface $userRepository
    ) {
        $this->companyManagement = $companyManagement;
        $this->addressRepository = $addressRepository;
        $this->userRepository = $userRepository;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customerId = (int) $context->getUserId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return null;
        }

        $statusLabels = [
            CompanyStatus::STATUS_PENDING => 'Pending Approval',
            CompanyStatus::STATUS_APPROVED => 'Approved',
            CompanyStatus::STATUS_REJECTED => 'Rejected',
            CompanyStatus::STATUS_BLOCKED => 'Blocked',
        ];

        $companyId = (int) $company->getEntityId();
        $users = $this->userRepository->getByCompanyId($companyId);

        return [
            'entity_id' => $company->getEntityId(),
            'company_name' => $company->getCompanyName(),
            'legal_name' => $company->getLegalName(),
            'company_email' => $company->getCompanyEmail(),
            'vat_tax_id' => $company->getVatTaxId(),
            'reseller_id' => $company->getResellerId(),
            'phone' => $company->getPhone(),
            'website' => $company->getWebsite(),
            'status' => $company->getStatus(),
            'status_label' => $statusLabels[$company->getStatus()] ?? 'Unknown',
            'user_count' => count($users),
        ];
    }
}
