<?php
/*
  $Id: login.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
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
}
?>