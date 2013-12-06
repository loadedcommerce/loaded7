<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_settings.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Product_settings extends lC_Access {
  var $_module = 'products',
      $_group = 'products',
      $_icon = 'products.png',
      $_title,
      $_sort_order = 800;

  public function lC_Access_Product_settings() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_product_settings_title');
    
    $this->_subgroups = array(array('icon' => 'image_groups.png',
                                    'title' => $lC_Language->get('access_image_groups_title'),
                                    'identifier' => '?image_groups'),                                       
                              array('icon' => 'weight.png',
                                    'title' => $lC_Language->get('access_weight_classes_title'),
                                    'identifier' => '?weight_classes')
                                    );      
  }
}
?>