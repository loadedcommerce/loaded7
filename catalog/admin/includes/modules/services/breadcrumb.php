<?php
/*
  $Id: breadcrumb.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_breadcrumb_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends,
        $precedes;

    function lC_Services_breadcrumb_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/breadcrumb.php');

      $this->title = $lC_Language->get('services_breadcrumb_title');
      $this->description = $lC_Language->get('services_breadcrumb_description');
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