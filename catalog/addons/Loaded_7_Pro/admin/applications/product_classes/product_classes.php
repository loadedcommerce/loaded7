<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_classes.php v1.0 2013-08-08 datazen $
*/
require(DIR_FS_CATALOG . 'addons/Loaded_7_Pro/admin/applications/product_classes/classes/product_classes.php');

class lC_Application_Product_classes extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'product_classes',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title');
  }
}
?>