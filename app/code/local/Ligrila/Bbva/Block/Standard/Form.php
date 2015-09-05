<?php
class Ligrila_Bbva_Block_Standard_Form extends Mage_Payment_Block_Form {
	protected function _construct() {
		$this->setTemplate('bbva/form.phtml');
		parent::_construct();
	}
}