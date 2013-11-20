<?php
/**
  @package    catalog::install
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upgrade.php v1.0 2013-08-08 datazen $
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