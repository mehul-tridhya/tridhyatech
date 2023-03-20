<?php

namespace Tridhya\Database\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Recurring implements InstallSchemaInterface
{
	/**
	 * @var \Magento\Framework\Stdlib\DateTime\DateTime
	 */
	protected $dateTime;

	public function __construct(
		DateTime $dateTime
	) {
		$this->dateTime = $dateTime;
	}

	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$setup->startSetup();
		// $setup->getConnection()->query("INSERT INTO aureatelabs_logs SET log_datetime = '" . $this->dateTime->gmtTimestamp() . "'");
		$setup->endSetup();
	}
}