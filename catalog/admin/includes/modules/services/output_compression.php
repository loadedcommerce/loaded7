<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: output_compression.php v1.0 2013-08-08 datazen $
*/
class lC_Services_output_compression_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes = 'session';

  public function lC_Services_output_compression_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/output_compression.php');

    $this->title = $lC_Language->get('services_output_compression_title');
    $this->description = $lC_Language->get('services_output_compression_description');
  }

  public function install() {
    global $lC_Database;

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_public function, date_added) values ('GZIP Compression Level', 'SERVICE_OUTPUT_COMPRESSION_GZIP_LEVEL', '5', 'Set the GZIP compression level to this value (0=min, 9=max).', '6', '0', 'lc_cfg_set_output_compression_pulldown_menu())', now())");
  }

  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('SERVICE_OUTPUT_COMPRESSION_GZIP_LEVEL');
  }
}
?>