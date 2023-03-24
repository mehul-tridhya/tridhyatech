<?php

namespace Tridhyatech\ApiCrud\Block;

class Insert extends \Magento\Framework\View\Element\Template
{
    protected $_pageFactory;
    protected $_postLoader;
    protected $_coreRegistry;
    protected $_blogLoader;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Tridhyatech\ApiCrud\Model\BlogFactory $blogLoader,
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blogLoader = $blogLoader;
        $this->_coreRegistry = $coreRegistry;
        return parent::__construct($context);
    }

    public function execute()
    {
        return $this->_pageFactory->create();
    }
    public function getEditData()
    {
        $id = $this->_coreRegistry->registry('editId');
        $postData = $this->_blogLoader->create();
        $result = $postData->load($id);
        return $result;
    }
}
