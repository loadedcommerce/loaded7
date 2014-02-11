<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: image_groups.php v1.0 2013-08-08 datazen $
*/
  require($lC_Vqmod->modCheck('includes/applications/images/classes/images.php'));

class lC_Image_groups_Admin {
 /*
  * Returns the image groups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];

    $Qgroups = $lC_Database->query('select id, title from :table_products_images_groups where language_id = :language_id order by title');
    $Qgroups->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
    $Qgroups->bindInt(':language_id', $lC_Language->getID());
    $Qgroups->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
    $Qgroups->execute();

    $result = array('aaData' => array());
    while ( $Qgroups->next() ) {
      $group_name = $Qgroups->value('title');
      if ( $Qgroups->valueInt('id') == DEFAULT_IMAGE_GROUP_ID ) {
        $group_name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }
      $name = '<td>' . $group_name . '</td>';
      
      if (strtolower($group_name) != 'originals') {
        $action = '<td class="align-right vertical-center">
                     <span class="button-group">
                       <a href="' . ((int)($_SESSION['admin']['access']['product_settings'] < 3) ? '#' : 'javascript://" onclick="editGroup(\'' . $Qgroups->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['product_settings'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                     </span>
                     <span class="button-group">
                       <a href="' . ((int)($_SESSION['admin']['access']['product_settings'] < 4 || $Qgroups->valueInt('id') == DEFAULT_IMAGE_GROUP_ID) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $Qgroups->valueInt('id') . '\', \'' . urlencode($Qgroups->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['product_settings'] < 4 || $Qgroups->valueInt('id') == DEFAULT_IMAGE_GROUP_ID) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                     </span>
                   </td>';
      } else {
        $action = '<td></td>';
      }
      $result['aaData'][] = array("$name", "$action");
      
    }

    $Qgroups->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The image group id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null, $edit = false) {
    global $lC_Database, $lC_Language;

    $result = array();

    $result['titleCode'] = '';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['titleCode'] .= '<span class="input" style="width:95%"><label for="title[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('title[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
    }

    if (isset($id) && $id != null) {
      $lC_ObjectInfo = new lC_ObjectInfo(lC_Image_groups_Admin::getData($id));

      $status_name = array();
      $Qgd = $lC_Database->query('select language_id, title from :table_products_images_groups where id = :id');
      $Qgd->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
      $Qgd->bindInt(':id', $id);
      $Qgd->execute();
      while ( $Qgd->next() ) {
        $status_name[$Qgd->valueInt('language_id')] = $Qgd->value('title');
      }
      $result['editTitleCode'] = '';
      foreach ( $lC_Language->getAll() as $l ) {
        $result['editTitleCode'] .= '<span class="input" style="width:95%"><label for="title[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('title[' . $l['id'] . ']', (isset($status_name[$l['id']]) ? $status_name[$l['id']] : null), 'class="input-unstyled"') . '</span><br />';
      }
      $result['code'] = $lC_ObjectInfo->get('code');
      $result['width'] = $lC_ObjectInfo->get('size_width');
      $result['height'] = $lC_ObjectInfo->get('size_height');
      $result['force_size'] = $lC_ObjectInfo->get('force_size');
    }

    return $result;
  }
 /*
  * Returns the image group information
  *
  * @param integer $id The image group id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    $Qgroup = $lC_Database->query('select * from :table_products_images_groups where id = :id and language_id = :language_id');
    $Qgroup->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
    $Qgroup->bindInt(':id', $id);
    $Qgroup->bindInt(':language_id', $lC_Language->getID());
    $Qgroup->execute();

    $data = $Qgroup->toArray();

    $Qgroup->freeResult();

    return $data;
  }
 /*
  * Saves the image group information
  *
  * @param integer $id The image group id used on update, null on insert
  * @param array $data An array containing the image group information
  * @param boolean $default True = set the image group to be the default
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data, $default = false) {
    global $lC_Database, $lC_Language;

    if ( is_numeric($id) ) {
      $group_id = $id;
    } else {
      $Qgroup = $lC_Database->query('select max(id) as id from :table_products_images_groups');
      $Qgroup->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
      $Qgroup->execute();

      $group_id = $Qgroup->valueInt('id') + 1;
    }

    $error = false;

    $lC_Database->startTransaction();

    foreach ( $lC_Language->getAll() as $l ) {
      if ( is_numeric($id) ) {
        $Qgroup = $lC_Database->query('update :table_products_images_groups set title = :title, code = :code, size_width = :size_width, size_height = :size_height, force_size = :force_size where id = :id and language_id = :language_id');
      } else {
        $Qgroup = $lC_Database->query('insert into :table_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) values (:id, :language_id, :title, :code, :size_width, :size_height, :force_size)');
      }

      $Qgroup->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
      $Qgroup->bindInt(':id', $group_id);
      $Qgroup->bindValue(':title', $data['title'][$l['id']]);
      $Qgroup->bindValue(':code', $data['code']);
      $Qgroup->bindInt(':size_width', $data['width']);
      $Qgroup->bindInt(':size_height', $data['height']);
      $Qgroup->bindInt(':force_size', ( $data['force_size'] == 'on' ) ? 1 : 0);
      $Qgroup->bindInt(':language_id', $l['id']);
      $Qgroup->setLogging($_SESSION['module'], $group_id);
      $Qgroup->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $group_id);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_IMAGE_GROUP_ID');
        $Qupdate->setLogging($_SESSION['module'], $group_id);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('images_groups');

      if ( $default === true ) {
        lC_Cache::clear('configuration');
      }

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }

 /*
  * Deletes the image group record
  *
  * @param integer $id The image group id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qdel = $lC_Database->query('delete from :table_products_images_groups where id = :id');
    $Qdel->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
    $Qdel->bindInt(':id', $id);
    $Qdel->setLogging($_SESSION['module'], $id);
    $Qdel->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch deletes image group records
  *
  * @param array $batch An array of image group id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language;

    $lC_Language->loadIniFile('image_groups.php');

    $result = array();
    $result['namesString'] = '';
    foreach ( $batch as $id ) {
      if ( $id == DEFAULT_IMAGE_GROUP_ID ) {
        $gData = lC_Image_groups_Admin::getData($id);
        $result['namesString'] .= $gData['title'] . ' (' . $lC_Language->get('default') . ')';
      } else {
        lC_Image_groups_Admin::delete($id);
      }
    }

    return $result;
  }
}
?>