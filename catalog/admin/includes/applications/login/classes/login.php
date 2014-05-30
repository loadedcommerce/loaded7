<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: login.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('../includes/classes/transport.php'));
require_once($lC_Vqmod->modCheck('includes/applications/updates/classes/updates.php')); 
require_once($lC_Vqmod->modCheck('includes/applications/store/classes/store.php')); 

class lC_Login_Admin {
 /*
  * Validate the admin login credentials
  *
  * @access public
  * @return array
  */
  public static function validate($user, $pass) {
    global $lC_Database; 
         
    $validated = false;
    if ( !empty($user) && !empty($pass) ) {
      $Qadmin = $lC_Database->query('select id, user_name, user_password from :table_administrators where user_name = :user_name');
      $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qadmin->bindValue(':user_name', $user);
      $Qadmin->execute();
      if ( $Qadmin->numberOfRows() > 0) {
        if ( lc_validate_password($pass, $Qadmin->value('user_password')) ) {  
          $validated = true;
        }
      }
    } 
    // check serial once per day and download any missing addons
    $serial = (defined('INSTALLATION_ID') && INSTALLATION_ID != NULL) ? INSTALLATION_ID : NULL;
    if ($serial != NULL) {
      if (self::_timeToCheck() || (isset($_SESSION['pro_success']) && $_SESSION['pro_success'] == true) ) {
        self::validateSerial($serial);
        if (isset($_SESSION['pro_success'])) unset($_SESSION['pro_success']);
      }
    }
   
    return $validated;
  }
 /*
  * Validate the admin login credentials
  *
  * @access public
  * @return array
  */
  public static function lostPasswordConfirmEmail($email) {
    global $lC_Database, $lC_Language;
    
    $lC_Language->loadIniFile('login.php');
    
    // check for email
    $Qadmin = $lC_Database->query('select * from :table_administrators where user_name = :user_name limit 1');
    $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qadmin->bindValue(':user_name', $email);
    $Qadmin->execute();

    $admin = $Qadmin->toArray();

    // if email exists we continue
    if ( $Qadmin->numberOfRows() > 0) {
      $lC_Database->startTransaction();
      
      $verify_key = utility::generateUID();
    
      // set the key to be verified from the resulting email
      $Qsetkey = $lC_Database->query('update :table_administrators set verify_key = :verify_key where user_name = :user_name');
      $Qsetkey->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qsetkey->bindValue(':user_name', $email);
      $Qsetkey->bindValue(':verify_key', $verify_key);
      $Qsetkey->setLogging($_SESSION['module'], $email);
      $Qsetkey->execute();
      
      if ( !$lC_Database->isError() ) {
        $lC_Database->commitTransaction();
        
        $_SESSION['user_not_exists'] = null;
        $_SESSION['user_confirmed_email'] = $email;
        
        // set email contents
        $email_text = '';
        $email_text .= sprintf($lC_Language->get('text_lost_password_verification_body_line_1'), $admin['first_name']) . "\n\n";
        $email_text .= sprintf($lC_Language->get('text_lost_password_verification_body_line_2'), $admin['user_name']) . "\n\n";
        $email_text .= sprintf($lC_Language->get('text_lost_password_verification_body_line_3'), lc_href_link_admin(FILENAME_DEFAULT, 'login&action=lost_password&email=' . $admin['user_name'] . '&key=' . $verify_key)) . "\n\n";
        $email_text .= sprintf($lC_Language->get('text_lost_password_verification_body_line_4'), $verify_key) . "\n\n";
        $email_text .= $lC_Language->get('text_lost_password_verification_body_line_5') . "\n\n";
        $email_text .= $lC_Language->get('text_lost_password_verification_body_line_6') . "\n\n";
        $email_text .= sprintf($lC_Language->get('text_lost_password_verification_body_line_7'), STORE_NAME) . "\n\n";
        
        // send verification email
        lc_email($Qadmin->valueProtected('first_name') . ' ' . $Qadmin->valueProtected('last_name'), $Qadmin->valueProtected('user_name'), sprintf($lC_Language->get('text_lost_password_verification_subject'), STORE_NAME), $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        
        return true;
      } else {
        $lC_Database->rollbackTransaction();
        
        $_SESSION['user_not_exists'] = true; 
        $_SESSION['user_confirmed_email'] = null;
        
        return false;
      }
    } else {
      $_SESSION['user_not_exists'] = true; 
      $_SESSION['user_confirmed_email'] = null;
       
      return false;
    }
  }
 /*
  * Validate the lost password key
  *
  * @access public
  * @return array
  */
  public static function lostPasswordConfirmKey($key, $email) {
    global $lC_Database;
       
    // check for key
    $Qkey = $lC_Database->query('select verify_key from :table_administrators where user_name = :user_name');
    $Qkey->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qkey->bindValue(':verify_key', $key);
    $Qkey->bindValue(':user_name', $email);
    $Qkey->execute();
    
    // if email exists we continue
    if ( $Qkey->numberOfRows() > 0) {
      if ($Qkey->value('verify_key') === $key) {
        $_SESSION['verify_key_valid'] = true;
        return true;
      } else {
        $_SESSION['verify_key_valid'] = false;
        return false;
      }
    } else {
      $_SESSION['verify_key_valid'] = false;
      return false;
    }
  }
 /*
  * Change the password and log the user in
  *
  * @access public
  * @return array
  */
  public static function passwordChange($pass, $email) { 
    global $lC_Database;
    
    $lC_Database->startTransaction();
    
    // update the password
    $Qpass = $lC_Database->query('update :table_administrators set user_password = :user_password where user_name = :user_name');
    $Qpass->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qpass->bindValue(':user_password', lc_encrypt_string(trim($pass)));
    $Qpass->bindValue(':user_name', $email);
    $Qpass->setLogging($_SESSION['module'], $email);
    $Qpass->execute();
    
    // successful password update, move on
    if ( !$lC_Database->isError() ) {
      // get user info
      $Qadmin = $lC_Database->query('select * from :table_administrators where user_name = :user_name');
      $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qadmin->bindValue(':user_name', $email);
      $Qadmin->execute();
      
      // set session info
      $_SESSION['admin'] = array('id' => $Qadmin->valueInt('id'),
                                 'firstname' => $Qadmin->value('first_name'),
                                 'lastname' => $Qadmin->value('last_name'),
                                 'username' => $Qadmin->value('user_name'),
                                 'password' => $Qadmin->value('user_pasword'),
                                 'access' => lC_Access::getUserLevels($Qadmin->valueInt('access_group_id')));
                                 
      // remove key to stop further changes with this key
      $Qkeyremove = $lC_Database->query('update :table_administrators set verify_key = :verify_key where user_name = :user_name');
      $Qkeyremove->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qkeyremove->bindValue(':user_name', $email);
      $Qkeyremove->bindValue(':verify_key', null);
      $Qkeyremove->execute();
      
      $lC_Database->commitTransaction();
      
      $_SESSION['user_confirmed_email'] = null;
      $_SESSION['user_not_exists'] = null;
    
      return true;
    } else {
      $lC_Database->rollbackTransaction();
      
      return false;
    }
  }
 /*
  * Activate Pro Serial
  *
  * @access public
  * @return json
  */
  public static function validateSerial($serial) {
    
    $result = array();
    
    $validateArr = array('serial' => $serial,
                         'storeName' => STORE_NAME,
                         'storeEmail' => STORE_OWNER_EMAIL_ADDRESS,
                         'storeWWW' => HTTP_SERVER . DIR_WS_HTTP_CATALOG);
                         
    $checksum = hash('sha256', json_encode($validateArr));
    $validateArr['checksum'] = $checksum;
    
    $api_version = (defined('API_VERSION') && API_VERSION != NULL) ? API_VERSION : '1_0';
    $resultXML = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/' . $api_version . '/check/serial/', 'method' => 'post', 'parameters' => $validateArr));  
    
    $resultArr = utility::xml2arr($resultXML);
    
    if (isset($resultArr['data']['valid']) && $resultArr['data']['valid'] == '1') {
      $result['rpcStatus'] = '1';
      // make sure the products for this serial have been downloaded from the cloud
      $products = (is_array($resultArr['data']['products'])) ? $resultArr['data']['products']['line_0'] : $resultArr['data']['products'];
      if (isset($products) && empty($products) === false) self::verifyProductsAreDownloaded($products);
    } else {
      $result['rpcStatus'] = $resultArr['data']['rpcStatus'];  
    }
    
    return $result;
  }
 /*
  * Returns the api check status
  *
  * @access public
  * @return boolean true or false
  */ 
  public static function apiCheck() {
    $api_version = (defined('API_VERSION') && API_VERSION != NULL) ? API_VERSION : '1_0';
    $apiCheck = transport::getResponse(array('url' => 'https://api.loadedcommerce.com/' . $api_version . '/updates/available/?ver=' . utility::getVersion() . '&ref=' . $_SERVER['SCRIPT_FILENAME'], 'method' => 'get'));
    $versions = utility::xml2arr($apiCheck);
    
    if ($versions == null) {
      $file = @fopen(DIR_FS_WORK . 'apinocom.tmp', "w");
      @fclose($file);
    }
  }  
 /*
  * Download the product PHARs
  *
  * @access private
  * @return boolean
  */ 
  public static function verifyProductsAreDownloaded($products) {
    
    $productsArr = explode('|', $products);
    
    $cnt=0;
    foreach ($productsArr as $key => $product) {
      
      $parts = explode(':', $product);
      $type = $parts[0];
      $item = $parts[1];
      
      if ($type == 'template') {
        if (!file_exists(DIR_FS_ADMIN . 'includes/templates/' . $item . '.php')) {
          // get the template phar and apply it
        }  
      } else { // addon
        if (!file_exists(DIR_FS_CATALOG . 'addons/' . $item . '/controller.php')) {
          // download the addon phar
          lC_Store_Admin::getAddonPhar($item);
          
          // apply the phar package
          if (file_exists(DIR_FS_WORK . 'addons/' . $item . '.phar')) {
            lC_Updates_Admin::applyPackage(DIR_FS_WORK . 'addons/' . $item . '.phar');
          }
        }
      }
      $cnt++;
    }
  }
 /*
  * Get the Pro/B2B Version Tag
  *
  * @access public
  * @return string
  */   
  public static function getProVersionTag() {
    global $lC_Language;
    
    if (utility::isB2B()) {   
      return '<small class="tag orange-gradient mid-margin-left mid-margin-right">B2B</small>' . $lC_Language->get('text_version') . ' ' . utility::getB2BVersion();    
    } else {
      return '<small class="tag red-gradient mid-margin-left mid-margin-right">PRO</small>' . $lC_Language->get('text_version') . ' ' . utility::getProVersion();    
    }
  }  
 /**
  * Check to see if it's time to re-check validity; once per day
  *  
  * @access private      
  * @return boolean
  */   
  private static function _timeToCheck() {
    global $lC_Database;

    $check = (defined('INSTALLATION_ID') && INSTALLATION_ID != '') ? INSTALLATION_ID : NULL;
    if ($check == NULL) return TRUE;
    
    if (defined('ADDONS_SYSTEM_LOADED_7_PRO_STATUS') && !file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/controller.php')) return TRUE;
    if (defined('ADDONS_SYSTEM_LOADED_7_B2B_STATUS') && !file_exists(DIR_FS_CATALOG . 'addons/Loaded_7_B2B/controller.php')) return TRUE;
    
    $Qcheck = $lC_Database->query('select * from :table_configuration where configuration_key = :configuration_key limit 1');
    $Qcheck->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcheck->bindValue(':configuration_key', 'INSTALLATION_ID');
    $Qcheck->execute();  
    
    if ($Qcheck->value('date_added') == '0000-00-00 00:00:00') return TRUE;
    
    $today = substr(lC_DateTime::getShort(date("Y-m-d H:m:s")), 3, 2);
    $check = substr(lC_DateTime::getShort($Qcheck->value('last_modified')), 3, 2);
    
    $Qcheck->freeResult();

    return (((int)$today != (int)$check) ? TRUE : FALSE);   
  }  
}
?>