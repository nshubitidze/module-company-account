<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Shubo\CompanyAccount\Api\CompanyManagementInterface;
use Shubo\CompanyAccount\Api\CompanyUserRepositoryInterface;

class CompanyUsers implements ResolverInterface
{
    private CompanyManagementInterface $companyManagement;
    private CompanyUserRepositoryInterface $userRepository;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(
        CompanyManagementInterface $companyManagement,
        CompanyUserRepositoryInterface $userRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->companyManagement = $companyManagement;
        $this->userRepository = $userRepository;
        $this->customerRepository = $customerRepository;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customerId = (int) $context->getUserId();
        $company = $this->companyManagement->getCompanyByCustomerId($customerId);

        if (!$company) {
            return ['items' => [], 'total_count' => 0, 'page_info' => ['page_size' => 20, 'current_page' => 1, 'total_pages' => 0]];
        }

        $users = $this->userRepository->getByCompanyId((int) $company->getEntityId());
        $pageSize = $args['pageSize'] ?? 20;
        $currentPage = $args['currentPage'] ?? 1;
        $totalCount = count($users);

        $offset = ($currentPage - 1) * $pageSize;
        $pagedUsers = array_slice($users, $offset, $pageSize);

        $items = [];
        foreach ($pagedUsers as $user) {
            try {
                $customer = $this->customerRepository->getById($user->getCustomerId());
                $items[] = [
                    'user_id' => $user->getUserId(),
                    'customer_id' => $user->getCustomerId(),
                    'firstname' => $customer->getFirstname(),
                    'lastname' => $customer->getLastname(),
                    'email' => $customer->getEmail(),
                    'job_title' => $user->getJobTitle(),
                    'phone' => $user->getPhone(),
                    'status' => $user->getStatus(),
                    'is_company_admin' => $user->getIsCompanyAdmin(),
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        return [
            'items' => $items,
            'total_count' => $totalCount,
            'page_info' => [
                'page_size' => $pageSize,
                'current_page' => $currentPage,
                'total_pages' => (int) ceil($totalCount / $pageSize),
            ],
        ];
    }
}
