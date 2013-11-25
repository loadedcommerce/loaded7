<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: modules.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Modules extends lC_Access {
  var $_module = 'modules',
      $_group = 'configuration',
      $_icon = 'modules.png',
      $_title,
      $_sort_order = 800;

  public function lC_Access_Modules() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_modules_title');
    
    $this->_subgroups = array(array('icon' => 'payment.png',
                                    'title' => $lC_Language->get('access_modules_payment_title'),
                                    'identifier' => '?store&type=payment'),
                              array('icon' => 'shipping.png',
                                    'title' => $lC_Language->get('access_modules_shipping_title'),
                                    'identifier' => '?store&type=shipping'),       
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