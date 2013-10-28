<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_trail.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_checkout_trail extends lC_Modules {
  var $_title,
      $_code = 'checkout_trail',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_checkout_trail() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_ordering_steps_heading');
  }

  public function initialize() {
    global $lC_Language, $lC_Template, $lC_ShoppingCart;

    $steps = array();

    if ($lC_ShoppingCart->getContentType() != 'virtual' || (defined('SKIP_CHECKOUT_SHIPPING_PAGE') && SKIP_CHECKOUT_SHIPPING_PAGE == '1')) {
      $steps[] = array('title' => $lC_Language->get('box_ordering_steps_delivery'),
                       'code' => 'shipping',
                       'active' => (($lC_Template->getModule() == 'shipping') || ($lC_Template->getModule() == 'shipping_address') ? true : false));
    }

    $steps[] = array('title' => $lC_Language->get('box_ordering_steps_payment'),
                     'code' => 'payment',
                     'active' => (($lC_Template->getModule() == 'payment') || ($lC_Template->getModule() == 'payment_address') ? true : false));

    $steps[] = array('title' => $lC_Language->get('box_ordering_steps_confirmation'),
                     'code' => 'confirmation',
                     'active' => ($lC_Template->getModule() == 'confirmation' ? true : false));

    $steps[] = array('title' => $lC_Language->get('box_ordering_steps_complete'),
                     'active' => ($lC_Template->getModule() == 'success' ? true : false));


    $content = '';
    $counter = 0;
    foreach ($steps as $step) {
      $counter++;

      if (isset($step['code'])) {
        $content .= '<li class="box-checkout-trail-title">' . lc_link_object(lc_href_link(FILENAME_CHECKOUT, $step['code'], 'SSL'), $step['title']) . '</li>';
      } else {
        $content .= '<li class="box-checkout-trail-title">' . lc_link_object(lc_href_link('#'), $step['title']) . '</li>';
      }
    }

    $this->_content = $content;
  }
}
?>