<?php
/*
  $Id: process.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Login_Actions_process class controls the login action
*/
class lC_Application_Login_Actions_process extends lC_Application_Login {
  public function __construct() {
    global $lC_Database, $lC_Language, $lC_MessageStack;

    parent::__construct();

    if ( !empty($_POST['user_name']) && !empty($_POST['user_password']) ) {
      $Qadmin = $lC_Database->query('select * from :table_administrators where user_name = :user_name');
      $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qadmin->bindValue(':user_name', $_POST['user_name']);
      $Qadmin->execute();
      if ( $Qadmin->numberOfRows() > 0) {
        if ( lc_validate_password($_POST['user_password'], $Qadmin->value('user_password')) ) {
          $_SESSION['admin'] = array('id' => $Qadmin->valueInt('id'),
                                     'firstname' => $Qadmin->value('first_name'),
                                     'lastname' => $Qadmin->value('last_name'),
                                     'username' => $Qadmin->value('user_name'),
                                     'access' => lC_Access::getUserLevels($Qadmin->valueInt('access_group_id')));
          $get_string = null;

          if ( isset($_SESSION['redirect_origin']) ) {
            $get_string = http_build_query($_SESSION['redirect_origin']['get']);

            if (substr($get_string, -1) == '=') $get_string = substr($get_string, 0, -1);

            unset($_SESSION['redirect_origin']);
          }
          lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $get_string));
        }
      }
    }
    $_SESSION['error'] = true;
    $_SESSION['errmsg'] = $lC_Language->get('ms_error_login_invalid');
  }
}
?>
