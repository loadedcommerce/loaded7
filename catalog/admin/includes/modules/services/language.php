<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: language.php v1.0 2013-08-08 datazen $
*/
class lC_Services_language_Admin {
  var $title,
      $description,
      $uninstallable = false,
      $depends = 'session',
      $precedes;

  public function lC_Services_language_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/language.php');

    $this->title = $lC_Language->get('services_language_title');
    $this->description = $lC_Language->get('services_language_description');
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