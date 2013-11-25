<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: check.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/classes/image.php'));  

class lC_Image_Admin_check extends lC_Image_Admin {

  // Private variables
  var $_code = 'check';

  // Class constructor
  public function lC_Image_Admin_check() {
    global $lC_Language;

    parent::lC_Image_Admin();

    $lC_Language->loadIniFile('modules/image/check.php');

    $this->_title = $lC_Language->get('images_check_title');
  }

  // Private methods
  protected function _setHeader() {
    global $lC_Language;

    $this->_header = array($lC_Language->get('images_check_table_heading_groups'),
                           $lC_Language->get('images_check_table_heading_results'));
  }

  protected function _setData() {
    global $lC_Database;

    $counter = array();

    $Qimages = $lC_Database->query('select image from :table_products_images');
    $Qimages->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qimages->execute();

    while ($Qimages->next()) {
      foreach ($this->_groups as $group) {
        if (!isset($counter[$group['id']]['records'])) {
          $counter[$group['id']]['records'] = 0;
        }

        $counter[$group['id']]['records']++;

        if (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $group['code'] . '/' . $Qimages->value('image'))) {
          if (!isset($counter[$group['id']]['existing'])) {
            $counter[$group['id']]['existing'] = 0;
          }

          $counter[$group['id']]['existing']++;
        }
      }
    }

    foreach ($counter as $key => $value) {
      $this->_data[] = array($this->_groups[$key]['title'],
                             $value['existing'] . ' / ' . $value['records']);
    }
  }
}
?>