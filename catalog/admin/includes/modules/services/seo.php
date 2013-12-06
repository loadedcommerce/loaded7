<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: seo.php v1.0 2013-08-08 datazen $
*/
class lC_Services_seo_Admin {
  var $title,
      $description,
      $uninstallable = true,
      $depends,
      $precedes = 'session';

  public function lC_Services_seo_Admin() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/services/seo.php');

    $this->title = $lC_Language->get('services_seo_title');
    $this->description = $lC_Language->get('services_seo_description');
  }

  public function install() {
    global $lC_Database;
    
    $exists = false;
    
    if ($handle = opendir(DIR_FS_CATALOG)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry == '.htaccess') {
          $exists = true;
        } 
      }
      closedir($handle);
    }
    
    if ($exists == false) {
      $file = file_get_contents(DIR_FS_CATALOG . 'dot.htaccess');
      $file = str_replace('RewriteBase /', 'RewriteBase ' . DIR_WS_HTTP_CATALOG, $file);
      file_put_contents(DIR_FS_CATALOG . 'dot.htaccess', $file);
      if (rename(DIR_FS_CATALOG . 'dot.htaccess', DIR_FS_CATALOG . '.htaccess')) {
      } else {
        return false;
      }
    } else {
      return false;
    }
    
    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_public function, set_public function, date_added) VALUES ('Add Category Parent Permalinks?', 'SERVICE_SEO_URL_ADD_CATEGORY_PARENT', '-1', 'Add each parent permalink to the url structure as you drill down into categories and products?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    
    lC_Cache::clear('category_tree');
    lC_Cache::clear('templates');
  }

  public function remove() {
    global $lC_Database;
    
    if ($handle = opendir(DIR_FS_CATALOG)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry == '.htaccess') {
          if (rename(DIR_FS_CATALOG . '.htaccess', DIR_FS_CATALOG . 'dot.htaccess')) {
          } else {
            return false;
          }
        }
      }
      closedir($handle);
    }

    $lC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    
    lC_Cache::clear('category_tree');
    lC_Cache::clear('templates');
  }

  public function keys() {
    return array('SERVICE_SEO_URL_ADD_CATEGORY_PARENT');
  }
}
?>