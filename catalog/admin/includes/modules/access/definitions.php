<?php
/*
  $Id: definitions.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Definitions extends lC_Access {
    var $_module = 'definitions',
        $_group = 'configuration',
        $_icon = 'definitions.png',
        $_title,
        $_sort_order = 600;

    function lC_Access_Definitions() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_definitions_title');
      
      $this->_subgroups = array(array('icon' => 'customer_groups.png',
                                      'title' => $lC_Language->get('access_customer_groups_title'),
                                      'identifier' => '?customer_groups'),
                                array('icon' => 'order_status.png',
                                      'title' => $lC_Language->get('access_orders_status_title'),
                                      'identifier' => '?orders_status'),
                                array('icon' => 'image_groups.png',
                                      'title' => $lC_Language->get('access_image_groups_title'),
                                      'identifier' => '?image_groups'),                                       
                                array('icon' => 'weight.png',
                                      'title' => $lC_Language->get('access_weight_classes_title'),
                                      'identifier' => '?weight_classes')
                                      );      
    }
  }
?>