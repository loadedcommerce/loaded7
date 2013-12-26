<?php
/**
  @package    admin
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: index.php v1.0 2013-08-08 datazen $
*/
require('includes/application_top.php');
require($lC_Vqmod->modCheck('includes/classes/template.php'));

$_SESSION['module'] = 'index';
$_SESSION['moduleType'] = 'core';

if ( !empty($_GET) ) {
  $first_array = array_slice($_GET, 0, 1);
  $_module = lc_sanitize_string(basename(key($first_array)));
  
  if ( file_exists('includes/applications/' . $_module . '/' . $_module . '.php') ) {
    $_SESSION['module'] = $_module;
    $_SESSION['moduleType'] = 'core';
  } else if (lC_Addons_Admin::hasAdminModule($_module)) {
    $_SESSION['module'] = $_module;
    $_SESSION['moduleType'] = 'addon';
  }
}

if ($_SESSION['moduleType'] == 'addon') {
  $lC_Language->loadIniFile(lC_Addons_Admin::getAdminLanguageDefinitionsPath($_SESSION['module']), null, null, true);
  require($lC_Vqmod->modCheck(lC_Addons_Admin::getAdminModule($_SESSION['module']))); 
} else {
  $lC_Language->loadIniFile($_SESSION['module'] . '.php');
  require($lC_Vqmod->modCheck('includes/applications/' . $_SESSION['module'] . '/' . $_SESSION['module'] . '.php'));
}

$lC_Template = lC_Template_Admin::setup($_SESSION['module']);
$lC_Template->set('default');

define(DIR_WS_TEMPLATE_IMAGES, HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . DIR_WS_ADMIN . 'templates/default/images/');

require($lC_Vqmod->modCheck('templates/default.php'));

require($lC_Vqmod->modCheck('includes/application_bottom.php'));
?>