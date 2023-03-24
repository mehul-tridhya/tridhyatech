<?php 


namespace Tridhyatech\ApiCrud\Block;

use Magento\Framework\View\Element\Template;
use Tridhyatech\ApiCrud\Model\BlogFactory;

class Index extends Template
{
    protected $_blogFactory;

    public function __construct(
        Template\Context $context,
        BlogFactory $blogFactory
    ) {
        parent::__construct($context);
        $this->_blogFactory = $blogFactory;
    }

    public function getBlogData()
    {
        $blog = $this->_blogFactory->create();
        $collection = $blog->getCollection();
        return $collection;
    }
}