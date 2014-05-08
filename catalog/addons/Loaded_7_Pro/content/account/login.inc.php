<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: login.inc.php v1.0 2013-08-08 datazen $
*/
if (utility::isPro() === true) {
  	
	function _validateAdminPassword($plain, $encrypted) {
	  if (!empty($plain) && !empty($encrypted)) {  
	    if (strstr($encrypted, '::')) {  // sha256 hash
  // split apart the hash / salt
  $stack = explode('::', $encrypted);

  if (sizeof($stack) != 2) {
    return false;
  }

  if (hash('sha256', $stack[1] . $plain) == $stack[0]) {
    return true;
  }      

} else { // legacy md5 hash - will be removed in production release       
  // split apart the hash / salt
  $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) {
        return false;
      }

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }  
  }

  return false;
}		

function validateAdminPassword($password) {
  global $lC_Database;
  	
  $Qadmin = $lC_Database->query('select user_password from :table_administrators');
  $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
  $Qadmin->execute();
  
  $validated = false;
  while ( $Qadmin->next()) {
    if ( _validateAdminPassword($password, $Qadmin->value('user_password')) ) {  
      $validated = true;
	  break;
    }
  }	
  
  return $validated;
}

// check for admin session
if (isset($_SESSION['admin_login'])) unset($_SESSION['admin_login']);	
if (validateAdminPassword($_POST['password'])) {
  $_SESSION['admin_login'] = true;
	}
  }
?>