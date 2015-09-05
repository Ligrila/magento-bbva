<?php
class Ligrila_Bbva_Block_Standard_Callback extends Mage_Core_Block_Template {

	protected function _construct() {
		parent::_construct();
		$session = Mage::getSingleton('checkout/session');
		$order	= Mage::getModel('sales/order')->load($session->getLastOrderId());
		$status = $order->bbva_status;
		$paymentProcessed = true;

		if ($status == 'ERROR' ){
			$message = $order->bbva_message;
			$session->addError($message);
			$callback = Mage::getUrl('checkout/cart');
		} else{
			if (! is_null($status) && $status == 'SUCCESS' ){
				$callback = Mage::getUrl('checkout/onepage/success');
			} else if($status == 'SIN_PROCESAR'){
				$callback = Mage::getUrl('bbva/standard/callback');
				$paymentProcessed = false;
			} else{
				$callback = Mage::getUrl('/');
			}
		}

		$this->setTemplate('bbva/callback.phtml');
		$this->assign("callbackUrl", $callback );
		$this->assign("paymentProcessed", $paymentProcessed );
	}
}