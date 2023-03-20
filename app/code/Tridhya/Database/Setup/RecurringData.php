<?php

namespace Tridhya\Database\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class RecurringData implements InstallDataInterface
{
	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		// Recurring data event logic
	}
}