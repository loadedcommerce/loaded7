<?php
/**
  @package    catalog
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account.php v1.0 2013-08-08 datazen $
*/
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require('includes/application_top.php');

if ($lC_Customer->isLoggedOn() === false) {
  if (!empty($_GET)) {
    $first_array = array_slice($_GET, 0, 1);
  }
  if (empty($_GET) || (!empty($_GET) && !in_array(lc_sanitize_string(basename(key($first_array))), array('login', 'create', 'password_forgotten')))) {
    $lC_NavigationHistory->setSnapshot();

    lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
  }
}

// VQMOD-hookpoint; DO NOT MODIFY OR REMOVE THE LINE BELOW
$lC_Language->load('account');

if ($lC_Services->isStarted('breadcrumb')) {
  $lC_Breadcrumb->add($lC_Language->get('breadcrumb_my_account'), lc_href_link(FILENAME_ACCOUNT, null, 'SSL'));
}

$lC_Template = lC_Template::setup('account');

$countries_array = array(array('id' => '', 'text' => $lC_Language->get('pull_down_default')));
foreach (lC_Address::getCountries() as $country) {
  $countries_array[] = array('id' => $country['id'], 'text' => $country['name']);
}

require($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '.php'));

require($lC_Vqmod->modCheck('includes/application_bottom.php'));
?>