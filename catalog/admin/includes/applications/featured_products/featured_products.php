<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/featured_products/classes/featured_products.php'));
require_once($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
$lC_Currencies = new lC_Currencies();

class lC_Application_Featured_products extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'featured_products',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Database, $lC_Language, $fInfo;

    $this->_page_title = $lC_Language->get('heading_title');
    
    $action = (isset($_GET['action']) && empty($_GET['action']) === false) ? preg_replace('/[^a-z\s]/', '', $_GET['action']) : NULL;
    
    switch ($action) {
      case 'save' :
        if ( is_numeric($_GET[$this->_module]) ) {
          
          $fInfo = new lC_ObjectInfo(lC_Featured_products_Admin::get($_GET[$this->_module]));
          
          $Qfpd = $lC_Database->query('select * from :table_featured_products_description where id = :id');
          $Qfpd->bindTable(':table_featured_products_description', TABLE_FEATURED_PRODUCTS_DESCRIPTION);
          $Qfpd->bindInt(':id', $fInfo->get('id'));
          $Qfpd->execute();
          
          $name = array();
          while ($Qfpd->next()) {
            $name[$Qfpd->valueInt('language_id')] = $Qfpd->value('name');
          }
          $fInfo->set('name', $name);
        }      
        break;
    }
  }
  
  public function getName($fInfo, $language_id = '1') {
    global $lC_Language;
    
    if (!is_object($fInfo)) return false;
    $nameArr = $fInfo->get('name');

    return $nameArr[$language_id];
  }
}
?>