<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Shubo\CompanyAccount\Model\Authorization\CompanyPermission;

class MyPermissions implements ResolverInterface
{
    private CompanyPermission $companyPermission;

    public function __construct(CompanyPermission $companyPermission)
    {
        $this->companyPermission = $companyPermission;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        return $this->companyPermission->getCustomerPermissions((int) $context->getUserId());
    }
}
