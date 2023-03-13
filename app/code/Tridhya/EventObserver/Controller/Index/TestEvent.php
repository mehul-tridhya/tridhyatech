<?php
namespace Tridhya\EventObserver\Controller\Index;
class TestEvent extends \Magento\Framework\App\Action\Action
{
	public function execute()
	{
		$textDisplay = new \Magento\Framework\DataObject(array('text' => 'Tridhya'));
		$this->_eventManager->dispatch('tridhya_event_observer_observer', ['mp_text' => $textDisplay]);
		echo $textDisplay->getText();
		exit;
	}
}