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
    
    $Qlevels = $lC_Database->query('select * from :table_customers_access order by id');
    $Qlevels->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qlevels->execute();

    $result = array('aaData' => array());
    while ( $Qlevels->next() ) {
      
      $system = ($Qlevels->valueInt('id') == 1 || $Qlevels->valueInt('id') == 2) ? true : false;
      
      $id = $Qlevels->valueInt('id');
      $level = $Qlevels->value('level') . '</th>';
      $members =  self::getMembers($Qlevels->valueInt('id'));
      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 3 || $system) ? '#' : 'javascript://" onclick="editCustomerAccessGroup(\'' . $Qlevels->valueInt('id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 3 || $system) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   </span>      
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 4 || $system) ? '#' : 'javascript://" onclick="deleteCustomerAccessGroup(\'' . $Qlevels->valueInt('id') . '\', \'' . urlencode($Qlevels->valueProtected('level')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['b2b_settings'] < 4 || $system) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </td>';
  
      $result['aaData'][] = array("$id", "$level", "$members", "$action");
      $result['entries'][] = $Qlevels->toArray();
    }

    $Qlevels->freeResult();

    return $result;
  }
  
  public static function getMembers($id) {
    return rand(1, 100);
  }
  
  public static function save($data) {
            echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        die('00');
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