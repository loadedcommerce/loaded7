<?php
/**
  @package    catalog
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout.php v1.0 2013-08-08 datazen $
*/
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require('includes/application_top.php');

$lC_Language->load('checkout');

if ($lC_Services->isStarted('breadcrumb')) {
  $lC_Breadcrumb->add($lC_Language->get('breadcrumb_checkout'), lc_href_link(FILENAME_CHECKOUT, null, 'SSL'));
}

$lC_Template = lC_Template::setup('cart');

$countries_array = array(array('id' => '', 'text' => $lC_Language->get('pull_down_default')));
foreach (lC_Address::getCountries() as $country) {
  $countries_array[] = array('id' => $country['id'], 'text' => $country['name']);
}

require($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '.php'));

require($lC_Vqmod->modCheck('includes/application_bottom.php'));
?>