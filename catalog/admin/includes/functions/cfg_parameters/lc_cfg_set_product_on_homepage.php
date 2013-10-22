<?php
/*
  $Id: lc_cfg_set_product_on_homepage.php v1.0 2013-01-01 kiran $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
  function lc_cfg_set_product_on_homepage($default, $key = null) {
    global $lC_Database, $lC_Language;
    
    $css_class = 'class="input with-small-padding"';
    
    $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS products_id, products_name from :table_products_description where language_id = :language_id');
    $Qproducts->appendQuery('order by products_name');
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
	  $Qproducts->execute();

    $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . ']';

    $array = array();
    while ( $Qproducts->next() ) {
      $array[] = array('id' => $Qproducts->value('products_id'),
                       'text' => $Qproducts->value('products_name'));
    }
    
    return lc_draw_pull_down_menu($name, $array, $default, $css_class);
  }

?>