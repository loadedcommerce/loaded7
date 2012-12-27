<?php
/*
  $Id: account.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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

  require('templates/' . $lC_Template->getCode() . '.php');

  require('includes/application_bottom.php');
?>