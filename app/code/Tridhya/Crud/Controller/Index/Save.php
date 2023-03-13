<?php

namespace Tridhya\Crud\Controller\Index;

class Save extends \Magento\Framework\App\Action\Action
{
     protected $_pageFactory;
     protected $_contactFactory;

     public function __construct(
          \Magento\Framework\App\Action\Context $context,
          \Magento\Framework\View\Result\PageFactory $pageFactory,
          \Tridhya\Crud\Model\ContactFactory $contactFactory
     ){
          $this->_pageFactory = $pageFactory;
          $this->_contactFactory = $contactFactory;
          return parent::__construct($context);
     }

     public function execute()
     {
          if ($this->getRequest()->isPost()) {
               $input = $this->getRequest()->getPostValue();
               $postData = $this->_contactFactory->create();
               if (isset($input['editId'])) {
                    $id = $input['editId'];
               } else {
                    $id = 0;
               }
               if($id != 0){
                    $postData->load($id);
                    $postData->addData($input);
                    $postData->setId($id);
                    $postData->save();
                    $this->messageManager->addSuccessMessage("Data Updated successfully!");
               }else{
                    $postData->setData($input)->save();
                    $this->messageManager->addSuccessMessage("Data added successfully!");
               }
               return $this->_redirect('crud/index/index');
          }
          return $this->_redirect('crud/index/index');
     }
}