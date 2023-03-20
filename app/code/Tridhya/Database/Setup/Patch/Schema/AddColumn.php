<?php

/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tridhya\Database\Setup\Patch\Schema;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class AddColumn implements SchemaPatchInterface
{
    private $moduleDataSetup;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }


    public static function getDependencies()
    {
        return [];
    }


    public function getAliases()
    {
        return [];
    }


    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $tableName = $this->moduleDataSetup->getTable('intray_table2');
        if ($this->moduleDataSetup->getConnection()->isTableExists($tableName) == true) {
            $this->moduleDataSetup->getConnection()->addColumn(
                $tableName,
                'name',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment'  => 'Name',
                ]
            );
        }

        $this->moduleDataSetup->endSetup();
    }
}
