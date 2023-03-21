<?php
namespace Tridhya\Newmodule\Block\Frontend;

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

    public function getMyText()
    {
        return 'Hello world!';
    }
}