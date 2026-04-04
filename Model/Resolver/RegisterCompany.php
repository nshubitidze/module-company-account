<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\LocalizedException;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;

class RegisterCompany implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;

    public function __construct(CompanyManagementInterface $companyManagement)
    {
        $this->companyManagement = $companyManagement;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $input = $args['input'] ?? [];

        if (empty($input['company_name']) || empty($input['company_email'])) {
            throw new GraphQlInputException(__('Company name and email are required.'));
        }

        if (empty($input['admin_firstname']) || empty($input['admin_lastname']) ||
            empty($input['admin_email']) || empty($input['admin_password'])) {
            throw new GraphQlInputException(__('Admin user details are required.'));
        }

        $companyData = [
            'company_name' => $input['company_name'],
            'company_email' => $input['company_email'],
            'legal_name' => $input['legal_name'] ?? null,
            'vat_tax_id' => $input['vat_tax_id'] ?? null,
            'phone' => $input['phone'] ?? null,
            'website' => $input['website'] ?? null,
            'street_line1' => $input['street_line1'] ?? '',
            'street_line2' => $input['street_line2'] ?? null,
            'city' => $input['city'] ?? '',
            'region' => $input['region'] ?? null,
            'region_id' => $input['region_id'] ?? null,
            'postcode' => $input['postcode'] ?? '',
            'country_id' => $input['country_id'] ?? '',
        ];

        $adminData = [
            'firstname' => $input['admin_firstname'],
            'lastname' => $input['admin_lastname'],
            'email' => $input['admin_email'],
            'password' => $input['admin_password'],
        ];

        try {
            $company = $this->companyManagement->register($companyData, $adminData);

            return [
                'company' => [
                    'entity_id' => $company->getEntityId(),
                    'company_name' => $company->getCompanyName(),
                    'company_email' => $company->getCompanyEmail(),
                    'status' => $company->getStatus(),
                    'status_label' => 'Pending Approval',
                ],
                'message' => 'Your company registration has been submitted and is pending approval.',
            ];
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }
}
