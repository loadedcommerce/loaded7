<?php
/*
  $Id: info.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  $_SERVER['SCRIPT_FILENAME'] = __FILE__;

  require('includes/application_top.php');

  $lC_Language->load('info');

  if ($lC_Services->isStarted('breadcrumb')) {
    $lC_Breadcrumb->add($lC_Language->get('breadcrumb_information'), lc_href_link(FILENAME_INFO));
  }

  $lC_Template = lC_Template::setup('info');

  require('templates/' . $lC_Template->getCode() . '.php');

  require('includes/application_bottom.php');
?>