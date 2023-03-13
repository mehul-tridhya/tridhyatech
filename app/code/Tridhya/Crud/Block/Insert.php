<?php

namespace Tridhya\Crud\Block;

class Insert extends \Magento\Framework\View\Element\Template
{
    protected $_pageFactory;
    protected $_postLoader;
    protected $_coreRegistry;
    protected $_contactLoader;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Tridhya\Crud\Model\ContactFactory $contactLoader,
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_contactLoader = $contactLoader;
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
        $postData = $this->_contactLoader->create();
        $result = $postData->load($id);
        return $result;
    }
}
