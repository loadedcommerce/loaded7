<?php
/**  
*  $Id: checkout.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
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