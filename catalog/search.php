<?php
/*
  $Id: search.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
  $_SERVER['SCRIPT_FILENAME'] = __FILE__;

  require('includes/application_top.php');

  $lC_Language->load('search');

  if ($lC_Services->isStarted('breadcrumb')) {
    $lC_Breadcrumb->add($lC_Language->get('breadcrumb_search'), lc_href_link(FILENAME_SEARCH));
  }

  $lC_Template = lC_Template::setup('search');

  require('templates/' . $lC_Template->getCode() . '.php');

  require('includes/application_bottom.php');
?>