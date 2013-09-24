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
      $name = $Qclasses->value('name');
      if ( $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID ) {
        $name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }      
      
      $title = '<td>' . $name . '</td>';
      $comment = '<td>' . $Qclasses->value('comment') . '</td>';
      $usage = '<td>&nbsp;</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access']['product_settings'] < 3) ? '#' : 'javascript://" onclick="editClass(\'' . $Qclasses->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['product_settings'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access']['product_settings'] < 4 || $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID) ? '#' : 'javascript://" onclick="deleteClass(\'' . $Qclasses->valueInt('id') . '\', \'' . urlencode($Qclasses->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['product_settings'] < 4 || $Qclasses->valueInt('id') == DEFAULT_PRODUCT_CLASSES_ID ) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
                                  
      $result['aaData'][] = array("$title", "$comment", "$usage", "$action");
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
        $Qclasses = $lC_Database->query('select language_id, name, comment from :table_product_classes where id = :id');
        $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
        $Qclasses->bindInt(':id', $id);
        $Qclasses->execute();
        
        $status_name = array();
       $result['editComment'] = '';
        while ( $Qclasses->next() ) {
          $status_name[$Qclasses->valueInt('language_id')] = $Qclasses->value('name');
          $result['editComment'] = $Qclasses->value('comment');
        }   
        $result['editNames'] = '';
        foreach ( $lC_Language->getAll() as $l ) {
          $result['editNames'] .= '<span class="input" style="width:92%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', (isset($status_name[$l['id']]) ? $status_name[$l['id']] : null), 'class="input-unstyled"') . '</span><br />';
        } 
      } else {
        $Qclasses = $lC_Database->query('select count(*) as total from :table_products where product_class_id = :product_class_id');
        $Qclasses->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
        $Qclasses->bindInt(':product_class_id', $id);
        $Qclasses->execute();

        if ( $Qclasses->valueInt('total') > 0 ) {
          $result['totalProducts'] = $Qclasses->valueInt('total');
          $result['rpcStatus'] = -2;
        }
      }
    } else {
      $result['names'] = '';
      foreach ( $lC_Language->getAll() as $l ) {
        $result['names'] .= '<span class="input" style="width:92%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
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

    if ( is_numeric($id) ) {
      $product_class_id = $id;
    } else {
      $Qpc = $lC_Database->query('select max(id) as product_class_id from :table_product_classes');
      $Qpc->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
      $Qpc->execute();

      $product_class_id = $Qpc->valueInt('product_class_id') + 1;
    }
    
    foreach ( $lC_Language->getAll() as $l ) {
      if ( is_numeric($id) ) {
        $Qpc = $lC_Database->query('update :table_product_classes set name = :name where id = :id and language_id = :language_id');
      } else {
        $Qpc = $lC_Database->query('insert into :table_product_classes (name, comment, language_id) values (:name, :comment, :language_id)');
        $Qpc->bindValue(':comment', $data['comment']);
      }

      $Qpc->bindTable(':table_product_classes', TABLE_PRODUCT_CLASSES);
      $Qpc->bindInt(':id', $product_class_id);
      $Qpc->bindValue(':name', $data['name'][$l['id']]);
      $Qpc->bindInt(':language_id', $l['id']);
      $Qpc->setLogging($_SESSION['module'], $product_class_id);
      $Qpc->execute();      
      
      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $product_class_id);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_PRODUCT_CLASSES_ID');
        $Qupdate->setLogging($_SESSION['module'], $product_class_id);
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
}
?>