<?php
/*
  $Id: api.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

class lC_Api {
  /**
  * Perform the installation ID check
  *  
  * @access private      
  * @return string
  */ 
  public function healthCheck() {
//    if ($this->__timeToCheck()) {
      return $this->__doRegister();
//    }
  }
  /**
  * Register the new install with the LC API
  *  
  * @access private      
  * @return string
  */   
  private function __doRegister() {
    global $lC_Database, $lC_Cache;
    
    // register the install with LC API
    $registerArr = array('storeName' => STORE_NAME,
                         'storeEmail' => STORE_OWNER_EMAIL_ADDRESS,
                         'storeWWW' => HTTP_SERVER . DIR_WS_HTTP_CATALOG,
                         'storeSSL' => HTTPS_SERVER . DIR_WS_HTTPS_CATALOG,
                         'systemMetaData' => base64_encode(json_encode(lc_get_system_information())),
                         'serverMetaData' => (isset($_SERVER) && is_array($_SERVER)) ? base64_encode(json_encode($_SERVER)) : NULL,
                         'envMetaData' => (isset($_ENV) && is_array($_ENV)) ? base64_encode(json_encode($_ENV)) : NULL);                        
                         
    $checksum = hash('sha256', json_encode($registerArr));
    $registerArr['checksum'] = $checksum;
    
    $resultXML = $this->__sendToHost($registerArr, 'https://api.loadedcommerce.com/1_0/register/install/');
    $newInstallationID = (preg_match("'<installationID[^>]*?>(.*?)</installationID>'i", $resultXML, $regs) == 1) ? $regs[1] : NULL;
    
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
    $Qupdate->bindValue(':configuration_value', $newInstallationID);
    $Qupdate->bindValue(':configuration_description', 'Installation ID');      
    $Qupdate->bindValue(':configuration_group_id', '6');      
    $Qupdate->bindValue(':last_modified', date("Y-m-d H:m:s"));   
    $Qupdate->execute();  

    lC_Cache::clear('configuration');
    
    if ( $lC_Database->isError() ) {
      return utility::arr2xml(array('error' => TRUE, 'message' => 'There was an error processing the request.'));
    } else {    
      return utility::arr2xml(array('error' => FALSE, 'installationID' => $newInstallationID));
    }  
  }
  /**
  * Check to see if it's time to re-check installation validity
  *  
  * @access private      
  * @return boolean
  */   
  private function __timeToCheck() {
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
  /**
  * Send the data to the host 
  *  
  * @param array  $_data    The data array to process
  * @param string $_url     The endpoint URL
  * @param string $_action  Switch for POST or GET
  * @access private      
  * @return mixed
  */  
  protected function __sendToHost($_data = NULL, $_url, $_action = 'post') {
    
    $agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    if (strtolower($_action) == 'post') {
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $_data);
    } else {
      $params = (is_array($_data) && !empty($_data)) ? utility::arr2nvp($_data) : $_data;
      if (!empty($params)) $_url .= '?' . $params;
    }
    curl_setopt($ch, CURLOPT_URL, $_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
    if (!is_array($_data) && substr($_data, 0, 5) == '<?xml') { 
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    }

    $response = curl_exec($ch);
    
    if(!curl_errno($ch)) {
      $result = $response;
    } else { 
      $result = utility::arr2xml(array('error' => TRUE, 'message' => curl_errno($ch))); 
    }
    
    curl_close($ch);
    
    return $result;
  }  
}
?>