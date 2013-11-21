<?php
/**
  @package    catalog::install
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: install.php v1.0 2013-08-08 datazen $
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
require($lC_Vqmod->modCheck('templates/main_page.php'));
?>