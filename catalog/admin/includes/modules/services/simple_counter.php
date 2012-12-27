<?php
/*
  $Id: simple_counter.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_simple_counter_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends,
        $precedes;

    function lC_Services_simple_counter_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/simple_counter.php');

      $this->title = $lC_Language->get('services_simple_counter_title');
      $this->description = $lC_Language->get('services_simple_counter_description');
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