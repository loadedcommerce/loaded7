<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customer_groups.php v1.0 2013-08-08 datazen $
*/
class lC_Customer_groups_Admin {
 /*
  * Returns the customer groups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;
    
    $media = $_GET['media'];

    $Qgroups = $lC_Database->query('select cg.customers_group_id, cg.customers_group_name, cgd.baseline_discount from :table_customers_groups cg left join :table_customers_groups_data cgd on (cg.customers_group_id = cgd.customers_group_id) where cg.language_id = :language_id order by cg.customers_group_name');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qgroups->bindInt(':language_id', $lC_Language->getID());
    $Qgroups->execute();

    $result = array('aaData' => array());
    while ( $Qgroups->next() ) {
      $status_name = $Qgroups->value('customers_group_name');
      if ( $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID ) {
        $status_name .= '<small class="tag purple-gradient glossy margin-left">' . $lC_Language->get('default_entry') . '</small>';
      }
      $name = '<td>' . $status_name . '</td>';
      $base = '<td>' . number_format($Qgroups->valueDecimal('baseline_discount'), DECIMAL_PLACES) . '%</td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['customer_groups'] < 3) ? '#' : 'javascript://" onclick="editGroup(\'' . $Qgroups->valueInt('customers_group_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['customer_groups'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['customer_groups'] < 4 || $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $Qgroups->valueInt('customers_group_id') . '\', \'' . urlencode($Qgroups->valueProtected('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['customer_groups'] < 4 || $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID ) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
      $result['aaData'][] = array("$name", "$base", "$action");
      $result['entries'][] = $Qgroups->toArray();
    }

    $Qgroups->freeResult();

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
    global $lC_Database, $lC_Language, $lC_Addons;
    
    $lC_Language->loadIniFile('customer_groups.php');

    $result = array();
    if (isset($id) && $id != null) {
      if ($edit === true) {
        $Qcg = $lC_Database->query('select cg.language_id, cg.customers_group_name, cgd.* from :table_customers_groups cg left join :table_customers_groups_data cgd on (cg.customers_group_id = cgd.customers_group_id) where cg.customers_group_id = :customers_group_id');
        $Qcg->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
        $Qcg->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
        $Qcg->bindInt(':customers_group_id', $id);
        $Qcg->execute();
        
        $result['cgData'] = $Qcg->toArray();
        
        $Qcg->execute();
        $status_name = array();
        while ( $Qcg->next() ) {
          $status_name[$Qcg->valueInt('language_id')] = $Qcg->value('customers_group_name');
          $result['editBaseline'] = $Qcg->valueDecimal('baseline_discount');
        }   
        $result['editNames'] = '';
        foreach ( $lC_Language->getAll() as $l ) {
          $result['editNames'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('name[' . $l['id'] . ']', (isset($status_name[$l['id']]) ? $status_name[$l['id']] : null), 'class="input-unstyled"') . '</span><br />';
        } 
      } else {
        $Qcustomers = $lC_Database->query('select count(*) as total from :table_customers where customers_group_id = :customers_group_id');
        $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
        $Qcustomers->bindInt(':customers_group_id', $id);
        $Qcustomers->execute();

        if ( $Qcustomers->valueInt('total') > 0 ) {
          $result['totalCustomers'] = $Qcustomers->valueInt('total');
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
    
    $Qgroups = $lC_Database->query('select cg.*, cgd.* from :table_customers_groups cg left join :table_customers_groups_data cgd on (cg.customers_group_id = cgd.customers_group_id) where cg.customers_group_id = :customers_group_id and cg.language_id = :language_id order by cg.customers_group_name');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qgroups->bindInt(':customers_group_id', $id);
    $Qgroups->bindInt(':language_id', $lC_Language->getID());    

    $data = $Qgroups->toArray();

    $Qgroups->freeResult();

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
      $customers_group_id = $id;
      // ISSUE: if we add a new language, editing values does not save the new language.
      // To cure this, we delete the old records first, then re-insert instead of update.
      lC_Customer_groups_Admin::delete($customers_group_id);
    } else {
      $Qgroups = $lC_Database->query('select max(customers_group_id) as customers_group_id from :table_customers_groups');
      $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
      $Qgroups->execute();

      $customers_group_id = $Qgroups->valueInt('customers_group_id') + 1;
    }   

    foreach ( $lC_Language->getAll() as $l ) {
      $group_name = str_replace('&#37;','%',$data['name'][$l['id']]);
      $group_name = str_replace('&#64;','@',$group_name);
      $Qgroups = $lC_Database->query('insert into :table_customers_groups (customers_group_id, language_id, customers_group_name) values (:customers_group_id, :language_id, :customers_group_name)');
      $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
      $Qgroups->bindInt(':customers_group_id', $customers_group_id);
      $Qgroups->bindValue(':customers_group_name', $group_name);
      $Qgroups->bindInt(':language_id', $l['id']);
      $Qgroups->setLogging($_SESSION['module'], $customers_group_id);
      $Qgroups->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ( $default === true ) {
        $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $customers_group_id);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_CUSTOMERS_GROUP_ID');
        $Qupdate->setLogging($_SESSION['module'], $customers_group_id);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }
    
    $Qgdata = $lC_Database->query('insert into :table_customers_groups_data (customers_group_id, baseline_discount) values (:customers_group_id, :baseline_discount)');
    $Qgdata->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qgdata->bindInt(':customers_group_id', $customers_group_id);
    $Qgdata->bindFloat(':baseline_discount', $data['baseline']);
    $Qgdata->setLogging($_SESSION['module'], $customers_group_id);
    $Qgdata->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }    

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      if ( $default === true ) {
        lC_Cache::clear('configuration');
      }

      return $customers_group_id;
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

    $Qgroups = $lC_Database->query('delete from :table_customers_groups where customers_group_id = :customers_group_id');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindInt(':customers_group_id', $id);
    $Qgroups->setLogging($_SESSION['module'], $id);
    $Qgroups->execute();
    
    if ( $lC_Database->isError() ) {
      return false;
    }

    $Qgdata = $lC_Database->query('delete from :table_customers_groups_data where customers_group_id = :customers_group_id');
    $Qgdata->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qgdata->bindInt(':customers_group_id', $id);
    $Qgdata->setLogging($_SESSION['module'], $id);
    $Qgdata->execute();

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

    $lC_Language->loadIniFile('customer_groups.php');

    $Qgroups = $lC_Database->query('select customers_group_id, customers_group_name from :table_customers_groups where customers_group_id in (":customers_group_id") and language_id = :language_id order by customers_group_name');
    $Qgroups->bindTable(':table_customers_groups', TABLE_CUSTOMERS_GROUPS);
    $Qgroups->bindRaw(':customers_group_id', implode('", "', array_unique(array_filter(array_slice($batch, 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
    $Qgroups->bindInt(':language_id', $lC_Language->getID());
    $Qgroups->execute();

    $names_string = '';
    while ( $Qgroups->next() ) {
      $Qcustomers = $lC_Database->query('select count(*) as total from :table_customers where customers_group_id = :customers_group_id');
      $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomers->bindInt(':customers_group_id', $Qgroups->valueInt('customers_group_id'));
      $Qcustomers->execute();
      if ( $Qcustomers->valueInt('total') > 0 || $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID ) {
        if ($Qcustomers->valueInt('total') > 0) {
          $names_string .= $Qgroups->value('customers_group_name') . ' (' . $Qcustomers->valueInt('total') . ' ' . $lC_Language->get('customers') . '), ';
        }
        if ( $Qgroups->valueInt('customers_group_id') == DEFAULT_CUSTOMERS_GROUP_ID) {
          $names_string .= $Qgroups->value('customers_group_name') . ' (' . $lC_Language->get('default') . ') ,';
        }
      } else {
        lC_Customer_groups_Admin::delete($Qgroups->valueInt('customers_group_id'));
      }
      $Qcustomers->freeResult();
    }
    if ( !empty($names_string) ) {
      $names_string = substr($names_string, 0, -2);
    }
    $result['namesString'] = $names_string;

    $Qgroups->freeResult();

    return $result;
  }
  
}
?>