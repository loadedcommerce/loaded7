<?php 
/**  
*  $Id: account.php v1.0 2013-01-01 datazen $
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

if ($lC_Customer->isLoggedOn() === false) {
  if (!empty($_GET)) {
    $first_array = array_slice($_GET, 0, 1);
  }
  if (empty($_GET) || (!empty($_GET) && !in_array(lc_sanitize_string(basename(key($first_array))), array('login', 'create', 'password_forgotten')))) {
    $lC_NavigationHistory->setSnapshot();

    lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
  }
}

$lC_Language->load('account');

if ($lC_Services->isStarted('breadcrumb')) {
  $lC_Breadcrumb->add($lC_Language->get('breadcrumb_my_account'), lc_href_link(FILENAME_ACCOUNT, null, 'SSL'));
}

$lC_Template = lC_Template::setup('account');

$countries_array = array(array('id' => '', 'text' => $lC_Language->get('pull_down_default')));
foreach (lC_Address::getCountries() as $country) {
  $countries_array[] = array('id' => $country['id'], 'text' => $country['name']);
}

require('templates/' . $lC_Template->getCode() . '.php');

require('includes/application_bottom.php');
?>