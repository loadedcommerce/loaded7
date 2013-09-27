<?php
/*
  $Id: install.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
require('includes/application.php');

$page_contents = 'upgrade.php';
if(isset($_GET['debug'])){
	echo '<pre>#';
	print_r($_POST);
	echo '#<pre>';
}

if (isset($_GET['step']) && is_numeric($_GET['step'])) {
  switch ($_GET['step']) {
    case '1':
      $page_contents = 'upgrade_1.php';
      break;

    case '2':
      $page_contents = 'upgrade_2.php';
      break;

    case '3':
      $page_contents = 'upgrade_3.php';
      break;

    case '4':
      $page_contents = 'upgrade_4.php';
      break;

    case '5':
      $page_contents = 'upgrade_5.php';
      break;

    case '6':
      $page_contents = 'upgrade_6.php';
      break;

    case '7':
      $page_contents = 'upgrade_7.php';
      break;

 	}
}
require($lC_Vqmod->modCheck('templates/main_page.php'));
?>