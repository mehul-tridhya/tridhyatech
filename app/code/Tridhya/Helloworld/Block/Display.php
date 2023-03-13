<?php
namespace Tridhya\Helloworld\Block;

use Magento\Framework\View\Element\Template;

class Display extends Template
{
    protected $_postFactory;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Tridhya\Crud\Model\ContactFactory $postFactory
	)
	{
		$this->_postFactory = $postFactory;
		parent::__construct($context);
	}

	public function sayHello()
	{
		return __('Hello World');
	}

	public function getPostCollection(){
		$post = $this->_postFactory->create();
		return $post->getCollection();
	}
}