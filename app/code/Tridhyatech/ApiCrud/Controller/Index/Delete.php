<?php

namespace Tridhyatech\ApiCrud\Controller\Index;

class Delete extends \Magento\Framework\App\Action\Action
{
     protected $_pageFactory;
     protected $_request;
     protected $_blogFactory;

     public function __construct(
          \Magento\Framework\App\Action\Context $context,
          \Magento\Framework\View\Result\PageFactory $pageFactory,
          \Magento\Framework\App\Request\Http $request,
          \Tridhyatech\ApiCrud\Model\BlogFactory $blogFactory
     ){
          $this->_pageFactory = $pageFactory;
          $this->_request = $request;
          $this->_blogFactory = $blogFactory;
          return parent::__construct($context);
     }

     public function execute()
     {
          $id = $this->_request->getParam('id');
          $postData = $this->_blogFactory->create();
          $result = $postData->setId($id);
          $result = $result->delete();
          return $this->_redirect('apicrud/index/index');
     }
}