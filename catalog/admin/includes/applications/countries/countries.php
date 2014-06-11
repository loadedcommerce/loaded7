<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: countries.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/countries/classes/countries.php'));

class lC_Application_Countries extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'countries',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');

    if ( !empty($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ) {
      $this->_page_contents = 'zones.php';
      $this->_page_title = lC_Address::getCountryName($_GET[$this->_module]);
    }
  }
}
?>