<?php
/*
  $Id: core.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_core_Admin {
    var $title,
        $description,
        $uninstallable = false,
        $depends = 'currencies',
        $precedes;

    function lC_Services_core_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/core.php');

      $this->title = $lC_Language->get('services_core_title');
      $this->description = $lC_Language->get('services_core_description');
    }

    function install() {
      return false;
    }

    function remove() {
      return false;
    }

    function keys() {
      return false;
    }
  }
?>