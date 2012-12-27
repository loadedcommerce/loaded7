<?php
  /*
  $Id: product_variants.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Product_variants_Admin class manages product variants
*/
class lC_Product_variants_Admin {
 /*
  * Returns the product variant groups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $_module, $lC_Database, $lC_Language;

    $media = $_GET['media'];
    
    $Qgroups = $lC_Database->query('select id, title, sort_order from :table_products_variants_groups where languages_id = :languages_id order by sort_order, title');
    $Qgroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qgroups->bindInt(':languages_id', $lC_Language->getID());
    $Qgroups->execute();

    $result = array('aaData' => array());
    while ( $Qgroups->next() ) {
      $Qentries = $lC_Database->query('select count(*) as total from :table_products_variants_values where products_variants_groups_id = :products_variants_groups_id');
      $Qentries->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
      $Qentries->bindInt(':products_variants_groups_id', $Qgroups->valueInt('id'));
      $Qentries->execute();

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qgroups->valueInt('id') . '" id="' . $Qgroups->valueInt('id') . '"></td>';
      $group = '<td>' . lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qgroups->valueInt('id')), '<span class="icon-folder icon-orange"></span>&nbsp;' . $Qgroups->value('title')) . '</td>';
      $total = '<td>' . $Qentries->valueInt('total') . '</td>';
      $sort = '<td>' . $Qgroups->valueInt('sort_order') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? '#' : 'javascript://" onclick="editGroup(\'' . $Qgroups->valueInt('id') . '\')') . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $Qgroups->valueInt('id') . '\', \'' . urlencode($Qgroups->valueProtected('title')) . '\');') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$group", "$total", "$sort", "$action");

      $Qentries->freeResult();
    }

    $Qgroups->freeResult();

    return $result;
  }
 /*
  * Get the product variant group information used on the dialog forms
  *
  * @param integer $id The product variant group id
  * @access public
  * @return array
  */
  public static function getFormData($id = null) {
    global $lC_Language;

    $result = array();

    $lC_DirectoryListing = new lC_DirectoryListing('../includes/modules/variants');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('php');

    if ($id != null && is_numeric($id)) {
      $result['pvData'] = lC_Product_variants_Admin::getData($id, $lC_Language->getID());
    }

    $modules_array = array();
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $module = substr($file['name'], 0, strrpos($file['name'], '.'));
      $modules_array[$module] = $module;
    }
    $result['modulesArray'] = $modules_array;

    $result['groupNames'] = '';
    $result['editGroupNames'] = '';
    foreach ($lC_Language->getAll() as $l) {
      if ($id != null && is_numeric($id)) {
        $result['editGroupNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('group_name[' . $l['id'] . ']', $result['pvData']['title'], 'id="editGroupName[' . $l['id'] . ']" class="input-unstyled"') . '</span><br />';
      } else {
        $result['groupNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('group_name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
      }
    }

    return $result;
  }
 /*
  * Get the product variant group information
  *
  * @param integer $id The product variant group id
  * @param integer $language_id The language id
  * @param string $key The product variant group key
  * @access public
  * @return array
  */
  public static function getData($id, $language_id = null, $key = null) {
    global $lC_Database, $lC_Language;

    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }

    $Qgroup = $lC_Database->query('select * from :table_products_variants_groups where id = :id and languages_id = :languages_id');
    $Qgroup->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qgroup->bindInt(':id', $id);
    $Qgroup->bindInt(':languages_id', $language_id);
    $Qgroup->execute();

    $data = $Qgroup->toArray();

    $Qentries = $lC_Database->query('select count(*) as total_entries from :table_products_variants_values where products_variants_groups_id = :products_variants_groups_id');
    $Qentries->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qentries->bindInt(':products_variants_groups_id', $id);
    $Qentries->execute();

    $data['total_entries'] = $Qentries->valueInt('total_entries');

    $Qproducts = $lC_Database->query('select count(*) as total_products from :table_products_variants pv, :table_products_variants_values pvv where pvv.products_variants_groups_id = :products_variants_groups_id and pvv.id = pv.products_variants_values_id');
    $Qproducts->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
    $Qproducts->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qproducts->bindInt(':products_variants_groups_id', $id);
    $Qproducts->execute();

    $data['total_products'] = $Qproducts->valueInt('total_products');

    if ( empty($key) ) {
      return $data;
    } else {
      return $data[$key];
    }
  }
 /*
  * Save the product variant group
  *
  * @param integer $id The product variant group id to update, null on insert
  * @param array $data The product variant group information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;

    $error = false;

    if ( is_numeric($id) ) {
      $group_id = $id;
    } else {
      $Qcheck = $lC_Database->query('select max(id) as id from :table_products_variants_groups');
      $Qcheck->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
      $Qcheck->execute();

      $group_id = $Qcheck->valueInt('id') + 1;
    }

    $lC_Database->startTransaction();

    foreach ( $lC_Language->getAll() as $l ) {
      if ( is_numeric($id) ) {
        $Qgroup = $lC_Database->query('update :table_products_variants_groups set title = :title, sort_order = :sort_order, module = :module where id = :id and languages_id = :languages_id');
      } else {
        $Qgroup = $lC_Database->query('insert into :table_products_variants_groups (id, languages_id, title, sort_order, module) values (:id, :languages_id, :title, :sort_order, :module)');
      }

      $Qgroup->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
      $Qgroup->bindInt(':id', $group_id);
      $Qgroup->bindInt(':languages_id', $l['id']);
      $Qgroup->bindValue(':title', $data['group_name'][$l['id']]);
      $Qgroup->bindInt(':sort_order', $data['sort_order']);
      $Qgroup->bindValue(':module', $data['module']);
      $Qgroup->setLogging($_SESSION['module'], $group_id);
      $Qgroup->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete a product variant group
  *
  * @param integer $id The product variant group id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qdelete = $lC_Database->query('delete from :table_products_variants_values where products_variants_groups_id = :products_variants_groups_id');
    $Qdelete->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qdelete->bindInt(':products_variants_groups_id', $id);
    $Qdelete->setLogging($_SESSION['module'], $id);
    $Qdelete->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }

    if ( $error === false ) {
      $Qdelete = $lC_Database->query('delete from :table_products_variants_groups where id = :id');
      $Qdelete->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
      $Qdelete->bindInt(':id', $id);
      $Qdelete->setLogging($_SESSION['module'], $id);
      $Qdelete->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete product variant groups
  *
  * @param array $batch An array of product variant group id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language;

    $lC_Language->loadIniFile('product_variants.php');

    $result = array();
    $result['namesString'] = '';
    foreach ( $batch as $id ) {
      $pvData = lC_Product_variants_Admin::getFormData($id);
      if ( isset($pvData['pvData']['total_entries']) && $pvData['pvData']['total_entries'] > 0 ) {
        $result['namesString'] .= $pvData['pvData']['title'] . ' (' . $pvData['pvData']['total_entries'] . '), ';
      } else {
        lC_Product_variants_Admin::delete($id);
      }
    }
    if ( !empty($result['namesString']) ) {
      $result['namesString'] = substr($result['namesString'], 0, -2);
    }

    return $result;
  }
 /*
  * Returns the product variant entries datatable data for listings
  *
  * @param integer $id The product variant groups id
  * @access public
  * @return array
  */
  public static function getAllEntries($id) {
    global $lC_Database, $lC_Language;

    $media = $_GET['media'];

    $Qentries = $lC_Database->query('select id, title, sort_order from :table_products_variants_values where products_variants_groups_id = :products_variants_groups_id and languages_id = :languages_id order by sort_order, title');
    $Qentries->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qentries->bindInt(':products_variants_groups_id', $id);
    $Qentries->bindInt(':languages_id', $lC_Language->getID());
    $Qentries->execute();

    $result = array('aaData' => array());
    while ( $Qentries->next() ) {

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qentries->valueInt('id') . '" id="' . $Qentries->valueInt('id') . '"></td>';
      $entries = '<td>' . $Qentries->value('title') . '</td>';
      $sort = '<td>' . $Qentries->valueInt('sort_order') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $Qentries->valueInt('id') . '\')') . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access']['languages'] < 3) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_edit') . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qentries->valueInt('id') . '\', \'' . urlencode($Qentries->valueProtected('title')) . '\');') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['languages'] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$entries", "$sort", "$action");
    }

    $Qentries->freeResult();

    return $result;
  }
 /*
  * Get the product variant entry information used on the dialog forms
  *
  * @param integer $id The product variant group entry id
  * @access public
  * @return array
  */
  public static function getEntryFormData($id = null) {
    global $lC_Language;

    $result = array();

    if ($id != null && is_numeric($id)) {
      $result['pveData'] = lC_Product_variants_Admin::getEntry($id, $lC_Language->getID());
    }

    $result['entryNames'] = '';
    $result['editEntryNames'] = '';
    foreach ($lC_Language->getAll() as $l) {
      if ($id != null && is_numeric($id)) {
        $result['editEntryNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('entry_name[' . $l['id'] . ']', $result['pveData']['title'], 'id="editEntryName[' . $l['id'] . ']" class="input-unstyled"') . '</span><br />';
      } else {
        $result['entryNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('entry_name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
      }
    }

    return $result;
  }
 /*
  * Return the product variant entry information
  *
  * @param integer $id The product variant group entry id
  * @param integer $language_id The language id
  * @access public
  * @return array
  */
  public static function getEntry($id, $language_id = null) {
    global $lC_Database, $lC_Language;

    if ( empty($language_id) ) {
      $language_id = $lC_Language->getID();
    }

    $Qentry = $lC_Database->query('select * from :table_products_variants_values where id = :id and languages_id = :languages_id');
    $Qentry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qentry->bindInt(':id', $id);
    $Qentry->bindInt(':languages_id', $language_id);
    $Qentry->execute();

    $data = $Qentry->toArray();

    $Qproducts = $lC_Database->query('select count(*) as total_products from :table_products_variants where products_variants_values_id = :products_variants_values_id');
    $Qproducts->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
    $Qproducts->bindInt(':products_variants_values_id', $Qentry->valueInt('id'));
    $Qproducts->execute();

    $data['total_products'] = $Qproducts->valueInt('total_products');

    $Qproducts->freeResult();
    $Qentry->freeResult();

    return $data;
  }
 /*
  * Save the product variant group entry
  *
  * @param integer $id The product variant group entry id to update, null on insert
  * @param array $data The product variant group entry information
  * @access public
  * @return boolean
  */
  public static function saveEntry($id = null, $data) {
    global $lC_Database, $lC_Language;

    $error = false;

    if ( is_numeric($id) ) {
      $entry_id = $id;
    } else {
      $Qcheck = $lC_Database->query('select max(id) as id from :table_products_variants_values');
      $Qcheck->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
      $Qcheck->execute();

      $entry_id = $Qcheck->valueInt('id') + 1;
    }

    $lC_Database->startTransaction();

    foreach ( $lC_Language->getAll() as $l ) {
      if ( is_numeric($id) ) {
        $Qentry = $lC_Database->query('update :table_products_variants_values set title = :title, sort_order = :sort_order where id = :id and languages_id = :languages_id');
      } else {
        $Qentry = $lC_Database->query('insert into :table_products_variants_values (id, languages_id, products_variants_groups_id, title, sort_order) values (:id, :languages_id, :products_variants_groups_id, :title, :sort_order)');
        $Qentry->bindInt(':products_variants_groups_id', $data['product_variants']);
      }

      $Qentry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
      $Qentry->bindInt(':id', $entry_id);
      $Qentry->bindInt(':languages_id', $l['id']);
      $Qentry->bindValue(':title', $data['entry_name'][$l['id']]);
      $Qentry->bindInt(':sort_order', $data['sort_order']);
      $Qentry->setLogging($_SESSION['module'], $entry_id);
      $Qentry->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete a product variant group entry
  *
  * @param integer $id The product variant group entry id to delete
  * @param integer $group_id The product variant group id
  * @access public
  * @return boolean
  */
  public static function deleteEntry($id, $group_id) {
    global $lC_Database;

    $Qentry = $lC_Database->query('delete from :table_products_variants_values where id = :id and products_variants_groups_id = :products_variants_groups_id');
    $Qentry->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qentry->bindInt(':id', $id);
    $Qentry->bindInt(':products_variants_groups_id', $group_id);
    $Qentry->setLogging($_SESSION['module'], $id);
    $Qentry->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete product variant group entries
  *
  * @param array $batch An array of product variant group entry id's to delete
  * @param integer $group_id The product variant group id
  * @access public
  * @return array
  */
  public static function batchDeleteEntries($batch, $group_id) {
    global $lC_Language;

    $lC_Language->loadIniFile('product_variants.php');

    $result = array();
    $result['namesString'] = '';
    foreach ( $batch as $id ) {
      $pveData = lC_Product_variants_Admin::getEntryFormData($id);
      if ( isset($pveData['pveData']['total_products']) && $pveData['pveData']['total_products'] > 0 ) {
        $result['namesString'] .= $pveData['pveData']['title'] . ' (' . $pveData['pveData']['total_products'] . '), ';
      } else {
        lC_Product_variants_Admin::deleteEntry($id, $group_id);
      }
    }
    if ( !empty($result['namesString']) ) {
      $result['namesString'] = substr($result['namesString'], 0, -2);
    }

    return $result;
  }
}
?>