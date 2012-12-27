<?php
/*
  $Id: resize.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  require_once('includes/classes/image.php'); 

  class lC_Image_Admin_resize extends lC_Image_Admin {

    // Private variables
    var $_code = 'resize',
        $_has_parameters = true;

    // Class constructor
    function lC_Image_Admin_resize() {
      global $lC_Language;

      parent::lC_Image_Admin();

      $lC_Language->loadIniFile('modules/image/resize.php');

      $this->_title = $lC_Language->get('images_resize_title');
    }

    // Public methods
    function getParameters() {
      global $lC_Language;

      $groups = array();
      $groups_ids = array();

      foreach ($this->_groups as $group) {
        if ($group['id'] != '1') {
          $groups[] = array('text' => $group['title'],
                            'id' => $group['id']);

          $groups_ids[] = $group['id'];
        }
      }

      return array(array('key' => $lC_Language->get('images_resize_field_groups'),
                         'field' => lc_draw_pull_down_menu('groups[]', $groups, $groups_ids, 'multiple="multiple" size="5" class="input full-width"')),
                   array('key' => $lC_Language->get('images_resize_field_overwrite_images'),
                         'field' => lc_draw_checkbox_field('overwrite', '1', null, 'class="input"')));
    }

    // Private methods
    function _setHeader() {
      global $lC_Language;

      $this->_header = array($lC_Language->get('images_resize_table_heading_groups'),
                             $lC_Language->get('images_resize_table_heading_total_resized'));
    }

    function _setData() {
      global $lC_Database, $lC_Language;

      $overwrite = false;

      if (isset($_POST['overwrite']) && ($_POST['overwrite'] == '1')) {
        $overwrite = true;
      }

      if (!isset($_POST['groups']) || !is_array($_POST['groups'])) {
        return false;
      }

      $Qoriginals = $lC_Database->query('select image from :table_products_images');
      $Qoriginals->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qoriginals->execute();

      $counter = array();

      while ($Qoriginals->next()) {
        foreach ($this->_groups as $group) {
          if ( ($group['id'] != '1') && in_array($group['id'], $_POST['groups'])) {
            if (!isset($counter[$group['id']])) {
              $counter[$group['id']] = 0;
            }

            if ( ($overwrite === true) || !file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $group['code'] . '/' . $Qoriginals->value('image')) ) {
              $this->resize($Qoriginals->value('image'), $group['id']);

              $counter[$group['id']]++;
            }
          }
        }
      }

      foreach ($counter as $key => $value) {
        $this->_data[] = array($this->_groups[$key]['title'],
                               $value);
      }
    }
  }
?>
