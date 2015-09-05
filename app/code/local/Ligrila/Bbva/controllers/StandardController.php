<?php

/**
*
*	Implementación del TPV del bbva
*	@author Leandro Emanuel López <info@ligrila.com>
*/

class Ligrila_Bbva_StandardController extends Mage_Core_Controller_Front_Action {

	protected function _expireAjax() {
		if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
			$this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
			exit;
		}
	}

	/**
	 * Get singleton
	 *
	 * @return Ligrila_Bbva_Model_PaymentMethod
	 */
	public function getStandard() {
		return Mage::getSingleton('bbva/paymentMethod');
	}
	 
	/**
	 * When a customer chooses Bbva on Checkout/Payment page
	 *
	 */
	public function redirectAction() {
		$session = Mage::getSingleton('checkout/session');
		if($session->getLastOrderId()){
			$session->setBbvaStandardQuoteId($session->getQuoteId());
			$state	=	Mage::getModel('bbva/paymentMethod')->getConfigData('redirect_status');
			$order	=	Mage::getModel('sales/order')->load($session->getLastOrderId());
			/*
			PROTECCION DE PEDIDOS YA REALIZADOS
			*/
			if($order->bbva_status=='SUCCESS'){
				$this->_redirect('checkout/onepage/success');
				return;
			}
			$order->setState($state,$state,Mage::helper('bbva')->__('Entring to TPV'),false);
			$order->save();
			$this->loadLayout();
			$block = $this->getLayout()->createBlock('bbva/standard_redirect');
			$this->getLayout()->getBlock('content')->append($block);
			$this->renderLayout();
		}
	}

	public function responseAction(){
		$params = $this->getRequest()->getParams();
		if (!empty($params['peticion']) && ! empty($params['localizador'])){
			$bbva = Mage::getModel('bbva/paymentMethod');
			$xml = new SimpleXMLElement($params['peticion']);
			if (isset($xml->coderror)){
				// hay error
				$codigo = (int)$xml->coderror;
				$bbva->errorPeticion($params['localizador'],$xml->deserror);
			} else {
				$bbva->peticionCorrecta($xml,$params['localizador']);
			}
			
		} else{
			die("FATAL ERROR");
		}
	}
	
	public function callbackAction() {
		$this->loadLayout();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('bbva/standard_callback'));
 		$this->renderLayout();
	}
}