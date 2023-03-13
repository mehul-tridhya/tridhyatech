<?php

namespace Tridhya\Crud\Block;

class Edit extends \Magento\Framework\View\Element\Template
{
     protected $_pageFactory;
     protected $_coreRegistry;
     protected $_contactLoader;

     public function __construct(
          \Magento\Framework\View\Element\Template\Context $context,
          \Magento\Framework\View\Result\PageFactory $pageFactory,
          \Magento\Framework\Registry $coreRegistry,
          \Tridhya\Crud\Model\ContactFactory $contactLoader,
          array $data = []
     ){
          $this->_pageFactory = $pageFactory;
          $this->_coreRegistry = $coreRegistry;
          $this->_contactLoader = $contactLoader;
          return parent::__construct($context,$data);
     }

     public function execute()
     {
          return $this->_pageFactory->create();
     }

     public function getEditData()
     {
          $id = $this->_coreRegistry->registry('editId');
          $postData = $this->_contactLoader->create();
          $result = $postData->load(10);
          return $result;
     }
}