<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: coupons.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/coupons/classes/coupons.php'));
require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
$lC_Currencies = new lC_Currencies();

class lC_Application_Coupons extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'coupons',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Database, $lC_Language, $cInfo;

    $this->_page_title = $lC_Language->get('heading_title');
    
    $action = (isset($_GET['action']) && empty($_GET['action']) === false) ? preg_replace('/[^a-z\s]/', '', $_GET['action']) : NULL;
    
    switch ($action) {
      case 'save' :
        if ( is_numeric($_GET[$this->_module]) ) {
          
          $cInfo = new lC_ObjectInfo(lC_Coupons_Admin::get($_GET[$this->_module]));
          
          $Qcd = $lC_Database->query('select * from :table_coupons_description where coupons_id = :coupons_id');
          $Qcd->bindTable(':table_coupons_description', TABLE_COUPONS_DESCRIPTION);
          $Qcd->bindInt(':coupons_id', $cInfo->get('coupons_id'));
          $Qcd->execute();
          
          $name = array();
          while ($Qcd->next()) {
            $name[$Qcd->valueInt('language_id')] = $Qcd->value('name');
          }
          $cInfo->set('name', $name);
        }      
        break;
    }
  }
  
  public function getName($cInfo, $language_id = '1') {
    global $lC_Language;
    
    if (!is_object($cInfo)) return false;
    $nameArr = $cInfo->get('name');

    return $nameArr[$language_id];
  }
}
?>