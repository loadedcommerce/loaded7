<?php
/*
  $Id: definitions.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Product_settings extends lC_Access {
    var $_module = 'products',
        $_group = 'products',
        $_icon = 'products.png',
        $_title,
        $_sort_order = 800;

    function lC_Access_Product_settings() {
      global $lC_Language, $lC_Template;

      $this->_title = $lC_Language->get('access_product_settings_title');
      
      $lC_Template->setSubOf('product_settings');
      
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