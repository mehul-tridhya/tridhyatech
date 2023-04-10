<?php

namespace Tridhyatech\CsvExport\Block\Index;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;


class Index extends Template
{
    protected $_storeManager;
    protected $_transportBuilder;
    protected $_inlineTranslation;

    public function __construct(
        Template\Context $context,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $stateInterface,
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $stateInterface;
    }

    public function execute()
    {
        $this->sendEmail();
    }

    public function sendEmail()
    {
        $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->_storeManager->getStore()->getId());
        $templateVars = array(
            'store' => $this->_storeManager->getStore(),
        );
        $from = array('email' => "test@webkul.com", 'name' => 'Name of Sender');
        $this->_inlineTranslation->suspend();
        $to = array('john@webkul.com');
        $transport = $this->_transportBuilder->setTemplateIdentifier('low_stock_product_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->_inlineTranslation->resume();
    }
}
