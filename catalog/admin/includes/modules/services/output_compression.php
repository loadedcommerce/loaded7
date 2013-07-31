<?php
/*
  $Id: output_compression.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Services_output_compression_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends,
        $precedes = 'session';

    function lC_Services_output_compression_Admin() {
      global $lC_Language;

      $lC_Language->loadIniFile('modules/services/output_compression.php');

      $this->title = $lC_Language->get('services_output_compression_title');
      $this->description = $lC_Language->get('services_output_compression_description');
    }

    function install() {
      global $lC_Database;

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('GZIP Compression Level', 'SERVICE_OUTPUT_COMPRESSION_GZIP_LEVEL', '5', 'Set the GZIP compression level to this value (0=min, 9=max).', '6', '0', 'lc_cfg_set_output_compression_pulldown_menu())', now())");
    }

    function remove() {
      global $lC_Database;

      $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_OUTPUT_COMPRESSION_GZIP_LEVEL');
    }
  }
?>
