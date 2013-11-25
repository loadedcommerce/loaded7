<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_product_on_homepage.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_product_on_homepage($default, $key = null) {
  global $lC_Database, $lC_Language;
  
  $css_class = 'class="input with-small-padding mid-margin-top"';
  
  $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS products_id, products_name from :table_products_description where language_id = :language_id');
  $Qproducts->appendQuery('order by products_name');
  $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
  $Qproducts->bindInt(':language_id', $lC_Language->getID());
	$Qproducts->execute();

  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

  $array = array();
  $array[] = array('id' => '',
                   'text' => $lC_Language->get('text_select_product'));
  while ( $Qproducts->next() ) {
    $array[] = array('id' => $Qproducts->value('products_id'),
                     'text' => $Qproducts->value('products_name'));
  }
  
  return lc_draw_pull_down_menu($name, $array, $default, $css_class);
}
?>