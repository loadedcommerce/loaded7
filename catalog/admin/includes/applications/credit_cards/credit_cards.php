<?php
/*
  $Id: credit_cards.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Application_Credit_cards class manages the credit cards GUI
*/
global $lC_Vqmod;

require($lC_Vqmod->modCheck('includes/applications/credit_cards/classes/credit_cards.php'));

class lC_Application_Credit_cards extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'credit_cards',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
  }
}
?>