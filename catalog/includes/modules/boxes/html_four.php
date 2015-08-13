<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: html_four.php v1.0 2013-08-08 wa4u $
*/
class lC_Boxes_html_four extends lC_Modules {
  var $_title,
      $_code = 'html_four',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_html_four() {
    global $lC_Language;
    $this->_title = $lC_Language->get('box_html_four_heading');
  }

  public function initialize() {
    global $lC_Language, $lC_Template, $lC_Product;
      
      $this->_content = $lC_Language->get('box_html_four_content');
  }
   
}
?>