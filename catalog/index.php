<?php
/**
  @package    catalog
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: index.php v1.0 2013-08-08 datazen $
*/
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require('includes/application_top.php');

$lC_Language->load('index');

$lC_Template = lC_Template::setup('index');

require($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '.php'));

require($lC_Vqmod->modCheck('includes/application_bottom.php'));
?>