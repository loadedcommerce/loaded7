<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: b2b_settings.php v1.0 2013-08-08 datazen $
*/
class lC_B2b_settings_Admin {
 /*
  * Returns the datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getCustomersGroupAccessLevels() {
    global $lC_Language, $lC_Database, $_module;

    $media = $_GET['media'];
    
    $lC_Language->loadIniFile(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/admin/languages/' . $lC_Language->getCode() . '/b2b_settings.php', null, null, true);
    
    $Qlevels = $lC_Database->query('select * from :table_customers_access order by id');
    $Qlevels->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qlevels->execute();

    $result = array('aaData' => array());
    while ( $Qlevels->next() ) {
      
      $system = ($Qlevels->valueInt('id') == 1 || $Qlevels->valueInt('id') == 2) ? true : false;
      
      $id = $Qlevels->valueInt('id');
      $level = $Qlevels->value('level') . '</th>';
      $memberArr =  self::getCustomerAccessMembers($Qlevels->valueInt('id'));
      $members = $memberArr['members'];
      $status = '<span class="align-center" id="customerAccessGroupStatus_' . $Qlevels->valueInt('id') . '" onclick="updateCustomerAccessLevelStatus(\'' . $Qlevels->valueInt('id') . '\', \'' . (($Qlevels->valueInt('status') == 1) ? 0 : 1) . '\');">' . (($Qlevels->valueInt('status') == 1) ? '<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="' . $lC_Language->get('text_disable_level') . '"></span>' : '<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="' . $lC_Language->get('text_enable_level') . '"></span>') . '</span>'; 

      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 3 || $system) ? '#' : 'javascript://" onclick="editCustomerAccessGroup(\'' . $Qlevels->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 3 || $system) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   </span>      
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 4 || $system) ? '#' : 'javascript://" onclick="deleteCustomerAccessGroup(\'' . $Qlevels->valueInt('id') . '\', \'' . urlencode($Qlevels->valueProtected('level')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 4 || $system) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
  
      $result['aaData'][] = array("$id", "$level", "$members", "$status", "$action");
      $result['entries'][] = $Qlevels->toArray();
    }

    $Qlevels->freeResult();

    return $result;
  }
  
  public static function getCustomerAccessMembers($id) {
    global $lC_Database;

    $groups = array();
    
    $Qgroups = $lC_Database->query('select customers_group_id, customers_access_levels from :table_customers_groups_data');
    $Qgroups->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qgroups->execute();
    
    while($Qgroups->next()) {
      $levelsArr = explode(';', $Qgroups->value('customers_access_levels'));  
      if (in_array($id, $levelsArr)) $groups[] = $Qgroups->value('customers_group_id');
    }
    
    $cnt = 0;
    foreach($groups as $key => $cgID) {
      $Qcustomers = $lC_Database->query('select count(*) as total from :table_customers where customers_group_id = :customers_group_id');
      $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomers->bindInt(':customers_group_id', $cgID);
      $Qcustomers->execute();      
      
      $cnt = $cnt + $Qcustomers->valueInt('total'); 
      
      $Qcustomers->freeResult();
    }
    
    return array('members' => $cnt);
  }
 /*
  * Returns the b2b_settings information
  *
  * @access public
  * @return array
  */
  public static function get() {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $data = array();
    
    $Qcfg = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', 'B2B_SETTINGS_GUEST_CATALOG_ACCESS');
    $Qcfg->execute(); 
    
    $data['B2B_SETTINGS_GUEST_CATALOG_ACCESS'] = $Qcfg->value('configuration_value'); 
    
    $Qcfg->freeResult();
    
    $Qcfg = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', 'B2B_SETTINGS_ALLOW_SELF_REGISTER');
    $Qcfg->execute(); 
    
    $data['B2B_SETTINGS_ALLOW_SELF_REGISTER'] = $Qcfg->value('configuration_value'); 
    
    $Qcfg->freeResult(); 
    
    $Qcfg = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', 'B2B_SETTINGS_SHOW_GUEST_ONLY_MSRP');
    $Qcfg->execute(); 
    
    $data['B2B_SETTINGS_SHOW_GUEST_ONLY_MSRP'] = $Qcfg->value('configuration_value'); 
    
    $Qcfg->freeResult(); 
    
    $Qcfg = $lC_Database->query('select configuration_value from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', 'B2B_SETTINGS_SHOW_RETAIL_ONLY_MSRP');
    $Qcfg->execute(); 
    
    $data['B2B_SETTINGS_SHOW_RETAIL_ONLY_MSRP'] = $Qcfg->value('configuration_value'); 
    
    $Qcfg->freeResult();         
    
    return $data;
  }   
 /*
  * Saves the settings information
  *
  * @param array $data The settings data to save
  * @access public
  * @return boolean
  */
  public static function save($data) {
    global $lC_Database;
        
    if (!is_array($data)) return false;
    
    $error = false;

    $lC_Database->startTransaction();
        
    foreach ($data as $key => $value) {
      
      $const = 'B2B_SETTINGS_' . strtoupper($key);

      $Qcfg = $lC_Database->query('select configuration_id from :table_configuration where configuration_key = :configuration_key');
      $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qcfg->bindValue(':configuration_key', $const);
      $Qcfg->execute();

      $newValue = ($value == 'on') ? '1' : (string)$value;
      
      if ( $Qcfg->numberOfRows() === 1 ) {
        $Qsettings = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value, last_modified = now() where configuration_key = :configuration_key');
      } else {
        $Qsettings = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, date_added) values (:configuration_title, :configuration_key, :configuration_value, :configuration_description, :configuration_group_id, :date_added)');
        $Qsettings->bindValue(':configuration_title', ucwords(str_replace('_', ' ', $key)));
        $Qsettings->bindValue(':configuration_description', ucwords(str_replace('_', ' ', $key)));
        $Qsettings->bindInt(':configuration_group_id', 10);
        $Qsettings->bindRaw(':date_added', 'now()');
      }
      $Qsettings->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qsettings->bindValue(':configuration_value', $newValue);
      $Qsettings->bindValue(':configuration_key', $const);
      $Qsettings->setLogging($_SESSION['module'], $key);
      $Qsettings->execute();
      
      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }      
    }
      
    if ( $error === false ) {
      $lC_Database->commitTransaction();
      lC_Cache::clear('configuration');
      
      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;      
  }
 /*
  * Update customer access level status 
  * 
  * @param int $id  The customer access level id 
  * @param int $val The customer access level status 
  * @access public
  * @return true or false
  */
  public static function updateCustomerAccessLevelStatus($id, $val = 0) {
    global $lC_Database;
    
    $Qupdate = $lC_Database->query('update :table_customers_access set status = :status where id = :id');
    $Qupdate->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qupdate->bindInt(':status', $val);
    $Qupdate->bindInt(':id', $id);
    $Qupdate->execute();
      
    return true;
  }  
 /*
  * Save the customer access level information
  *
  * @param  string  $level  The customer access level to add
  * @access public
  * @return boolean
  */
  public static function addCustomerAccessLevel($level) {   
    global $lC_Database, $lC_Language;

    $error = false;

    $Qchk = $lC_Database->query('select id from :table_customers_access where level = :level limit 1');
    $Qchk->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qchk->bindValue(':level', $level);
    $Qchk->execute();  
    
    $rows = $Qchk->numberOfRows();

    $Qchk->freeResult();
    
    if ($rows == 0) {
    
      $lC_Database->startTransaction();

      $Qlevel = $lC_Database->query('insert into :table_customers_access (level, status) values (:level, :status)');
      $Qlevel->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
      $Qlevel->bindValue(':level', $level);
      $Qlevel->bindInt(':status', 1);
      $Qlevel->setLogging($_SESSION['module'], $level);
      $Qlevel->execute();

      if ( $lC_Database->isError() === false ) {
        $lC_Database->commitTransaction();
        return true;
      }

      $lC_Database->rollbackTransaction();

      return false;
    } else { 
      return true;
    }
  } 
 /*
  * Update the customer access level information
  *
  * @param  integer $id   The customer access level id to update
  * @param  array   $data The customer access level data
  * @access public
  * @return boolean
  */
  public static function updateCustomerAccessLevels($id, $data) {   
    global $lC_Database, $lC_Language;
   
    $error = false;
    
    $lC_Database->startTransaction();

    $Qlevel = $lC_Database->query('update :table_customers_access set level = :level, status = :status where id = :id');
    $Qlevel->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qlevel->bindValue(':level', $data['level']);
    $Qlevel->bindInt(':status', (($data['status'] == 'on') ? 1 : 0));
    $Qlevel->bindInt(':id', $id);
    $Qlevel->setLogging($_SESSION['module'], $data['level']);
    $Qlevel->execute();

    if ( $lC_Database->isError() === false ) {
      $lC_Database->commitTransaction();
      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }    
 /*
  * Delete the customer access level record
  *
  * @param integer $id The customer access level id to delete
  * @access public
  * @return boolean
  */
  public static function deleteCustomerAccessLevel($id) {
    global $lC_Database;

    $Qgroups = $lC_Database->query('delete from :table_customers_access where id = :id');
    $Qgroups->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qgroups->bindInt(':id', $id);
    $Qgroups->setLogging($_SESSION['module'], $id);
    $Qgroups->execute();
    
    if ( $lC_Database->isError() ) {
      return false;
    } 
    
    return true;
  }  
 /*
  * Get the customer access level data
  *
  * @param integer $id The customer access level id to retrieve
  * @access public
  * @return array
  */
  public static function getCustomerAccessFormData($id) {
    global $lC_Database;

    $Qgroups = $lC_Database->query('select * from :table_customers_access where id = :id limit 1');
    $Qgroups->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qgroups->bindInt(':id', $id);
    $Qgroups->execute();
    
    $data =  $Qgroups->toArray();
    
    $Qgroups->freeResult();
    
    return $data;
  }    
}
?>