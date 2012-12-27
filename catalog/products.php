<?php
/*
  $Id: products.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  $_SERVER['SCRIPT_FILENAME'] = __FILE__;

  require('includes/application_top.php');

  $lC_Language->load('products');

  $lC_Template = lC_Template::setup('products');

  require('templates/' . $lC_Template->getCode() . '.php');

  require('includes/application_bottom.php');
?>