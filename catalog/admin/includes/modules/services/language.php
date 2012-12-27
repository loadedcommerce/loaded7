<?php
/*
  $Id: language.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_language_Admin {
    var $title,
        $description,
        $uninstallable = false,
        $depends = 'session',
        $precedes;

    function lC_Services_language_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/language.php');

      $this->title = $lC_Language->get('services_language_title');
      $this->description = $lC_Language->get('services_language_description');
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