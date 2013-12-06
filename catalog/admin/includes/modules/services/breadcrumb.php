<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: breadcrumb.php v1.0 2013-08-08 datazen $
*/
class lC_Services_breadcrumb_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes;

  public function lC_Services_breadcrumb_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/breadcrumb.php');

    $this->title = $lC_Language->get('services_breadcrumb_title');
    $this->description = $lC_Language->get('services_breadcrumb_description');
  }

  public function install() {
    return false;
  }

  public function remove() {
    return false;
  }

  public function keys() {
    return false;
  }
}
?>