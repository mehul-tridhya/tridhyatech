<?php
namespace Tridhya\CustomCli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\State;

class Hellocustom extends Command
{
    protected $_storeManager;
    protected $_transportBuilder;
    protected $_inlineTranslation;
    protected $_state;

    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";

    public function __construct(
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $stateInterface,
        State $state,
    ) {
        parent::__construct();
        $this->_state = $state;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $stateInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    )
    {
        // $name = $input->getArgument(self::NAME_ARGUMENT);
        // $option = $input->getOption(self::NAME_OPTION);
        // $output->writeln("Hello " . $name);
        $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        $this->sendEmail();
        $output->writeln("Low Stock Mail is sent.");
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("dolphin_customcli:hellocustom");
        $this->setDescription("custom cli command");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
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