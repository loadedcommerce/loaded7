<?php
/**
  @package    catalog::install::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.php v1.0 2013-08-08 datazen $
*/
function lc_realpath($directory) {
  return str_replace('\\', '/', realpath($directory));
}
function ioncube_loader_version_array () {
  if (function_exists('ioncube_loader_iversion') ) {
    $ioncube_loader_iversion = ioncube_loader_iversion();
    $ioncube_loader_version_major = (int)substr($ioncube_loader_iversion, 0, 1);
    $ioncube_loader_version_minor = (int)substr($ioncube_loader_iversion, 1, 2);
    $ioncube_loader_version_revision = (int)substr($ioncube_loader_iversion, 3, 2);
    $ioncube_loader_version = "$ioncube_loader_version_major.$ioncube_loader_version_minor.$ioncube_loader_version_revision";
  } else {
    $ioncube_loader_version = ioncube_loader_version();
    $ioncube_loader_version_major = (int)substr($ioncube_loader_version, 0, 1);
    $ioncube_loader_version_minor = (int)substr($ioncube_loader_version, 2, 1);
  }
  return array('version' => $ioncube_loader_version, 'major' => $ioncube_loader_version_major, 'minor' => $ioncube_loader_version_minor);
}
function ioncube_test() {
  global $lC_Language;
  $status = "";
  $instructions = "";
  $status_class = "red";

  if (extension_loaded('ionCube Loader')) {
    $ioncube_loader_version = ioncube_loader_version_array();
    $status .= $lC_Language->get('ioncube_installed_version') . $ioncube_loader_version['version'];
    $status_icon = "<img src=\"templates/img/icons/tick.gif\" border=\"0\" width=\"16\" height=\"16\" class=\"small-margin-bottom\">";
    if ($ioncube_loader_version['major'] == 4 && $ioncube_loader_version['minor'] < 4 && $ioncube_loader_version['revision'] < 1) {
      $status_icon = "<img src=\"templates/img/icons/cross.gif\" border=\"0\" width=\"16\" height=\"16\" class=\"small-margin-bottom\">";
      $instructions .= $lC_Language->get('ioncube_update_needed');
      $status_class = "red";
      $ok = FALSE;
    } else {
      $instructions .= $lC_Language->get('ioncube_no_additional_config');
      $status_class = "green";
      $ok = TRUE;
    }
  } else {
    $status .= $lC_Language->get('text_not_installed');
    $status_icon = "<img src=\"templates/img/icons/cross.gif\" border=\"0\" width=\"16\" height=\"16\" class=\"small-margin-bottom\">";
    $instructions .= $lC_Language->get('ioncube_not_installed_instructions');
    $ok = FALSE;
  }                  

  $txt = "$status_icon
         <b><font color=\"$status_class\">ionCube Loader</font></b><br /> 
         <b>" . $lC_Language->get('text_status') . "</b> $status<br /> 
         <b>" . $lC_Language->get('text_instructions') . "</b> $instructions";
         
  $check = array('txt' => $txt,
                 'ok' => $ok);

  return $check;
}
?>