<?php

$installer = $this;
/* @var $installer Ligrila_Bbva_Model_Mysql4_Setup */

$installer->startSetup();

$installer->addAttribute('order', 'bbva_status', array());
$installer->addAttribute('order', 'bbva_message', array());
$installer->addAttribute('order', 'bbva_pago_id', array());
$installer->endSetup();



