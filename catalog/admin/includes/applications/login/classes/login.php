<?php
/*
  $Id: login.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Login_Admin class manages products expected
*/
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
   
    return $validated;
  }
 /*
  * Validate the admin login credentials
  *
  * @access public
  * @return array
  */
  public static function lostPasswordConfirmEmail($email) {
    global $lC_Database;
    
    // check for email
    $Qadmin = $lC_Database->query('select * from :table_administrators where user_name = :user_name');
    $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qadmin->bindValue(':user_name', $email);
    $Qadmin->execute();
    
    // if email exists we continue
    if ( $Qadmin->numberOfRows() > 0) {
      $lC_Database->startTransaction();
    
      // set the key to be verified from the resulting email
      $Qsetkey = $lC_Database->query('update :table_administrators set verify_key = :verify_key where user_name = :user_name');
      $Qsetkey->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qsetkey->bindValue(':user_name', $email);
      $Qsetkey->bindValue(':verify_key', Utility::generateUID());
      $Qsetkey->setLogging($_SESSION['module'], 'login');
      $Qsetkey->execute();
      
      if ( !$lC_Database->isError() ) {
        $lC_Database->commitTransaction();
        
        $_SESSION['user_not_exists'] = null;
        $_SESSION['user_confirmed_email'] = $email;
        
        // send off email here      
        
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
}




?>