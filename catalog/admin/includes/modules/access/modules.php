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

  class lC_Access_Modules extends lC_Access {
    var $_module = 'modules',
        $_group = 'configuration',
        $_icon = 'modules.png',
        $_title,
        $_sort_order = 800;

    function lC_Access_Modules() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_modules_title');
      
      $this->_subgroups = array(array('icon' => 'payment.png',
                                      'title' => $lC_Language->get('access_modules_payment_title'),
                                      'identifier' => '?modules_payment'),
                                array('icon' => 'shipping.png',
                                      'title' => $lC_Language->get('access_modules_shipping_title'),
                                      'identifier' => '?modules_shipping'),       
                                array('icon' => 'calculator.png',
                                      'title' => $lC_Language->get('access_modules_order_total_title'),
                                      'identifier' => '?modules_order_total'),  
                                array('icon' => 'locale.png',
                                      'title' => $lC_Language->get('access_modules_geoip_title'),
                                      'identifier' => '?modules_geoip'),  
                                array('icon' => 'services.png',
                                      'title' => $lC_Language->get('access_services_title'),
                                      'identifier' => '?services'),  
                                array('icon' => 'products.png',
                                      'title' => $lC_Language->get('access_product_attributes_title'),
                                      'identifier' => '?product_attributes')                                                                                                                                                                                         
                                      );      
    }
  }
?>