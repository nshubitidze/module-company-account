<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Creates default permission resources that can be assigned to company roles.
 * Note: Actual default roles are created per-company during company registration,
 * not as global data. This patch ensures the permission resource list is documented.
 */
class CreateDefaultRoles implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply(): self
    {
        // Default roles are created per-company in CompanyManagement::register()
        // This patch is a placeholder for any global setup data needed in the future.
        // The available permission resources are defined in
        // Model/Authorization/CompanyPermission::getAllPermissionResources()
        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
