<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: server_info.php v1.0 2013-08-08 datazen $
*/
class lC_Server_info_Admin {
 /*
  * Delete the customer group record
  *
  * @param integer $id The customer group id to delete
  * @access public
  * @return boolean
  */
  public static function updateInstallID($id) {
    global $lC_Database;

    // remove any old value that might be in the database
    $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key = :configuration_key');
    $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qdel->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qdel->execute();      

    // update configuration table and add the installation ID   
    $Qupdate = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, last_modified) values (:configuration_title, :configuration_key, :configuration_value, :configuration_description, :configuration_group_id, :last_modified)');
    $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qupdate->bindValue(':configuration_title', 'Installation ID');
    $Qupdate->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qupdate->bindValue(':configuration_value', $id);
    $Qupdate->bindValue(':configuration_description', 'Installation ID');      
    $Qupdate->bindValue(':configuration_group_id', '6');      
    $Qupdate->bindValue(':last_modified', date("Y-m-d H:m:s"));   
    $Qupdate->execute();  

    lC_Cache::clear('configuration');

    if ( $lC_Database->isError() ) {
      return false;
    } 
    
    return true;
  }
}
?>