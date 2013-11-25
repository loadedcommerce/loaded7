<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: api.php v1.0 2013-08-08 datazen $
*/
require_once(DIR_FS_ADMIN . 'includes/applications/server_info/classes/server_info.php');
require_once(DIR_FS_CATALOG . 'includes/classes/transport.php'); 

class lC_Api {
  /**
  * Perform the installation ID check
  *  
  * @access private      
  * @return string
  */ 
  public function healthCheck($data = array()) {
//    if ($this->_timeToCheck()) {
//      return $this->_doHealthCheck($data);
//    }
  }
  /**
  * Register the installation
  *  
  * @access private      
  * @return string
  */  
  public function register($data) {
    return $this->_doRegister($data);
  }  
  
  /**
  * Validate the serial
  *  
  * @access private      
  * @return string
  */  
  public function validateSerial($data) {
    return $this->_validateSerial($data);
  }  
  /**
  * Register the new install with the LC API
  *  
  * @access private      
  * @return string
  */   
  private function _doRegister($data) {
    global $lC_Database, $lC_Cache;
                  
    if (isset($data['activation_email']) && $data['activation_email'] != NULL) {
      $storeEmail = $data['activation_email'];
    } else {
      $storeEmail = STORE_OWNER_EMAIL_ADDRESS;
    } 
    
    if (isset($data['activation_serial']) && $data['activation_serial'] != NULL) {
      $storeSerial = $data['activation_serial'];
    } else {
      $storeSerial = '';
    }    
    
    // register the install with LC API
    $registerArr = array('serial' => $storeSerial,
                         'storeName' => STORE_NAME,
                         'storeEmail' => $storeEmail,
                         'storeWWW' => HTTP_SERVER . DIR_WS_HTTP_CATALOG,
                         'storeSSL' => HTTPS_SERVER . DIR_WS_HTTPS_CATALOG,
                         'systemMetaData' => base64_encode(json_encode(lc_get_system_information())),
                         'serverMetaData' => (isset($_SERVER) && is_array($_SERVER)) ? base64_encode(json_encode($_SERVER)) : NULL,
                         'envMetaData' => (isset($_ENV) && is_array($_ENV)) ? base64_encode(json_encode($_ENV)) : NULL);                        
                         
    $checksum = hash('sha256', json_encode($registerArr));
    $registerArr['checksum'] = $checksum;
    
    $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/1_0/register/install/', 'method' => 'post', 'parameters' => $registerArr));
    $newInstallationID = (preg_match("'<installationID[^>]*?>(.*?)</installationID>'i", $resultXML, $regs) == 1) ? $regs[1] : NULL;

    if ( lC_Server_info_Admin::updateInstallID($newInstallationID) ) {
      return utility::arr2xml(array('error' => FALSE, 'installationID' => $newInstallationID));
    } else {    
      return utility::arr2xml(array('error' => TRUE, 'message' => 'error processing the request'));
    }  
  }
  /**
  * Check to see if it's time to re-check installation validity
  *  
  * @access private      
  * @return boolean
  */   
  private function _timeToCheck() {
    global $lC_Database;

    $check = (defined('INSTALLATION_ID') && INSTALLATION_ID != '') ? INSTALLATION_ID : NULL;
    if ($check == NULL) return TRUE;
    
    $Qcheck = $lC_Database->query('select last_modified from :table_configuration where configuration_key = :configuration_key');
    $Qcheck->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcheck->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qcheck->execute();  
    
    $today = substr(lC_DateTime::getShort(date("Y-m-d H:m:s")), 3, 2);
    $check = substr(lC_DateTime::getShort($Qcheck->value('last_modified')), 3, 2);
    
    $Qcheck->freeResult();

    return (((int)$today != (int)$check) ? TRUE : FALSE);   
  }    
}
?>