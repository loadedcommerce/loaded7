<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_classes.php v1.0 2013-08-08 datazen $
*/
class lC_Product_classes_Admin {
 /*
  * Returns the datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;
    
    $media = $_GET['media'];
    
    $Qclasses = $lC_Database->query('select * from :table_product_classes where language_id = :language_id order by name');
    $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
    $Qclasses->bindInt(':language_id', $lC_Language->getID());
    $Qclasses->execute();

    $result = array('aaData' => array());
    while ( $Qclasses->next() ) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qclasses->valueInt('id') . '" id="' . $Qclasses->valueInt('id') . '"></td>';
      
      $name = $Qclasses->value('name');
      if ( $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID ) {
        $name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }      
      
      $title = '<td>' . $name . '</td>';
      $comment = '<td>' . $Qclasses->value('comment') . '</td>';
      $status = '<td><span id="status_' . $Qclasses->value('id') . '" onclick="updateStatus(\'' . $Qclasses->value('id') . '\', \'' . (($Qclasses->value('status') == 1) ? 0 : 1) . '\');">' . (($Qclasses->valueInt('status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable') . '"></span>') . '</span></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['product_settings'] < 3) ? '#' : 'javascript://" onclick="editClass(\'' . $Qclasses->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['product_settings'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['product_settings'] < 4 || $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID) ? '#' : 'javascript://" onclick="deleteClass(\'' . $Qclasses->valueInt('id') . '\', \'' . urlencode($Qclasses->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['product_settings'] < 4 || $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID ) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
                                  
      $result['aaData'][] = array("$check", "$title", "$comment", "$status", "$action");
      $result['entries'][] = $Qclasses->toArray();
    }

    $Qclasses->freeResult();

    return $result;
  }  
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The customer groups id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null, $edit = false) {
    global $lC_Database, $lC_Language;

    $result = array();
    if (isset($id) && $id != null) {
      if ($edit === true) {
        $Qclasses = $lC_Database->query('select language_id, name, comment, status from :table_product_classes where id = :id limit 1');
        $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
        $Qclasses->bindInt(':id', $id);
        $Qclasses->execute();
        
        $status_name = array();
        while ( $Qclasses->next() ) {
          $status_name[$Qclasses->valueInt('language_id')] = $Qclasses->value('name');
        }   
        $result['editNames'] = '';
        foreach ( $lC_Language->getAll() as $l ) {
          $result['editNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', (isset($status_name[$l['id']]) ? $status_name[$l['id']] : null), 'class="input-unstyled"') . '</span><br />';
        } 
      } else {
        $Qclasses = $lC_Database->query('select count(*) as total from :table_product_classes where id = :id');
        $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
        $Qclasses->bindInt(':id', $id);
        $Qclasses->execute();

        if ( $Qclasses->valueInt('total') > 0 ) {
          $result['totalClasses'] = $Qclasses->valueInt('total');
          $result['rpcStatus'] = -2;
        }
      }
    } else {
      $result['names'] = '';
      foreach ( $lC_Language->getAll() as $l ) {
        $result['names'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
      }
    }

    return $result;
  }
 /*
  * Get the customer group information
  *
  * @param integer $id The customer group id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;
    
    $Qclasses = $lC_Database->query('select* from :table_product_classes where id = :id and language_id = :language_id order by name');
    $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
    $Qclasses->bindInt(':id', $id);
    $Qclasses->bindInt(':language_id', $lC_Language->getID());    

    $data = $Qclasses->toArray();

    $Qclasses->freeResult();

    return $data;
  }
 /*
  * Save the customer group information
  *
  * @param integer $id The customer group id used on update, null on insert
  * @param array $data An array containing the customer group information
  * @param boolean $default True = set the customer group to be the default
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data, $default = false) {   
    global $lC_Database, $lC_Language;

    $error = false;

    $lC_Database->startTransaction();

    if ( isset($id) && $id != null ) {
      lC_Product_classes_Admin::delete($id);
    } else {
      $Qclasses = $lC_Database->query('select max(id) from :table_product_classes');
      $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
      $Qclasses->execute();

      $id = $Qclasses->valueInt('id') + 1;
    }

    foreach ( $lC_Language->getAll() as $l ) {
      $Qclasses = $lC_Database->query('insert into :table_product_classes (name, comment, status, language_id) values (:name, :comment, :status, :language_id)');
      $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
      $Qclasses->bindInt(':id', $id);
      $Qclasses->bindValue(':name', $data['name'][$l['id']]);
      $Qclasses->bindValue(':comment', $data['comment']);
      $Qclasses->bindInt(':status', $data['status']);
      $Qclasses->bindInt(':language_id', $l['id']);
      $Qclasses->setLogging($_SESSION['module'], $id);
      $Qclasses->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $id);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_PRODUCT_CLASSES_ID');
        $Qupdate->setLogging($_SESSION['module'], $id);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      if ( $default === true ) {
        lC_Cache::clear('configuration');
      }

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Delete the customer group record
  *
  * @param integer $id The customer group id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qclasses = $lC_Database->query('delete from :table_product_classes where id = :id');
    $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
    $Qclasses->bindInt(':id', $id);
    $Qclasses->setLogging($_SESSION['module'], $id);
    $Qclasses->execute();
    
    if ( $lC_Database->isError() ) {
      return false;
    }

    return true;
  }
 /*
  * Batch delete customer group records
  *
  * @param array $batch An array of customer group id's to delete
  * @access public
  * @return array
  */
  public static function batchDelete($batch) {
    global $lC_Language, $lC_Database;

    $lC_Language->loadIniFile(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/languages/' . $lC_Language->getCode() . '/product_classes.php');

    $Qclasses = $lC_Database->query('select id, name from :table_product_classes where id in (":id") and language_id = :language_id order by name');
    $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
    $Qclasses->bindRaw(':id', implode('", "', array_unique(array_filter(array_slice($batch, 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
    $Qclasses->bindInt(':language_id', $lC_Language->getID());
    $Qclasses->execute();

    $names_string = '';
    while ( $Qclasses->next() ) {
      $Qcustomers = $lC_Database->query('select count(*) as total from :table_products where product_class_id = :id');
      $Qcustomers->bindTable(':table_products', TABLE_PRODUCTS);
      $Qcustomers->bindInt(':id', $Qclasses->valueInt('id'));
      $Qcustomers->execute();
      if ( $Qcustomers->valueInt('total') > 0 || $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID ) {
        if ($Qcustomers->valueInt('total') > 0) {
          $names_string .= $Qclasses->value('name') . ' (' . $Qcustomers->valueInt('total') . ' ' . $lC_Language->get('products') . '), ';
        }
        if ( $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID) {
          $names_string .= $Qclasses->value('name') . ' (' . $lC_Language->get('default') . ') ,';
        }
      } else {
        lC_Product_classes_Admin::delete($Qclasses->valueInt('id'));
      }
      $Qcustomers->freeResult();
    }
    if ( !empty($names_string) ) {
      $names_string = substr($names_string, 0, -2);
    }
    $result['namesString'] = $names_string;

    $Qclasses->freeResult();

    return $result;
  }  
}
?>