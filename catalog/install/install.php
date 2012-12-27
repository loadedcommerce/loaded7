<?php
/*
  $Id: install.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require('includes/application.php');

$page_contents = 'install.php';

if (isset($_GET['step']) && is_numeric($_GET['step'])) {
  switch ($_GET['step']) {
    case '2':
      $page_contents = 'install_2.php';
      break;

    case '3':
      $page_contents = 'install_3.php';
      break;

    case '4':
      $page_contents = 'install_4.php';
      break;
  }
}
require('templates/main_page.php');
?>