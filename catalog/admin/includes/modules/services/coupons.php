<?php
/*
  $Id: coupons.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_coupons_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes;

  public function lC_Services_coupons_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/coupons.php');

    $this->title = $lC_Language->get('services_coupons_title');
    $this->description = $lC_Language->get('services_coupons_description');
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