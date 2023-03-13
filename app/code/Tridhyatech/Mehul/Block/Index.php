<?php
namespace Tridhyatech\Mehul\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template
{
    protected $_postFactory;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
	)
	{
		parent::__construct($context);
	}

    public function execute()
    {
        $resultPage = $this->_postFactory->create();
        return $resultPage;
    }
}