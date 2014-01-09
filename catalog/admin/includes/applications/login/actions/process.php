<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: process.php v1.0 2013-08-08 datazen $
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
                                     'password' => $Qadmin->value('user_password'),
                                     'access' => lC_Access::getUserLevels($Qadmin->valueInt('access_group_id')),
                                     'language_id' => $Qadmin->value('language_id'));
          $get_string = null;

          if ( isset($_SESSION['redirect_origin']) ) {
            $get_string = http_build_query($_SESSION['redirect_origin']['get']);

            if (substr($get_string, -1) == '=') $get_string = substr($get_string, 0, -1);

            unset($_SESSION['redirect_origin']);
          }
          
          if (defined('INSTALLATION_ID') && INSTALLATION_ID != NULL) {
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $get_string));
          } else {          
            // redirect to login=register
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, 'login&action=register'));
          }
        }
      }
    }
    $_SESSION['error'] = true;
    $_SESSION['errmsg'] = $lC_Language->get('ms_error_login_invalid');
  }
}
?>
