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
    global $lC_Database, $lC_Language, $fInfo, $products_array;

    $this->_page_title = $lC_Language->get('heading_title');
        
    $action = (isset($_GET['action']) && empty($_GET['action']) === false) ? preg_replace('/[^a-z\s]/', '', $_GET['action']) : NULL;
    
    switch ($action) {
      case 'save' :
        if ( is_numeric($_GET[$this->_module]) ) {          
          $fInfo = new lC_ObjectInfo(lC_Featured_products_Admin::get($_GET[$this->_module]));          
        }
        break;
    }
    
    $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS products_id, products_name from :table_products_description where language_id = :language_id order by products_name');
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();

    $products_array = array();
    $products_array[] = array('id' => '',
                              'text' => $lC_Language->get('text_select_product'));
    while ( $Qproducts->next() ) {
      $products_array[] = array('id' => $Qproducts->value('products_id'),
                                'text' => $Qproducts->value('products_name'));
    }
  }
}
?>