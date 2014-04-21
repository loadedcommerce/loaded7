<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: customer_groups.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

require_once($lC_Vqmod->modCheck('includes/applications/customer_groups/classes/customer_groups.php'));

class lC_Customer_groups_b2b_Admin extends lC_Customer_groups_Admin {
  
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The customer groups id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return array
  */
  public static function getFormData($id = null, $edit = false) {
    global $lC_Database, $lC_Language, $lC_Addons;
    
    $result = parent::getFormData($id, $edit);
    
    // get the access levels
    $Qlevels = $lC_Database->query('select * from :table_customers_access where status = :status');
    $Qlevels->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qlevels->bindInt(':status', 1);
    $Qlevels->execute();
    
    $accessHtml = '';
    while ($Qlevels->next()) {
      $accessHtml .= '<label class="label button-height"><input class="mid-margin-right switch tiny" type="checkbox" name="level[' . $Qlevels->valueInt('id') . ']" value="1" id="customer_access_level_' . $Qlevels->valueInt('id') . '" />' . $Qlevels->value('level') . '</label><br />';
    }
    
    $Qlevels->freeResult();
    
    // get the payment/shipping addons for the group
    $paymentHtml = '';
    $shippingHtml = '';    
    foreach($lC_Addons->getAddons('enabled') as $name => $data) {
      if ($data['type'] == 'payment' || $data['type'] == 'paypal') {
        $paymentHtml .= '<label class="label button-height"><input class="mid-margin-right switch tiny" type="checkbox" name="payment[' . $name . ']" value="1" id="payment_' . strtolower($name) . '" />' . str_replace('_', ' ', $name) . '</label><br />';
      } else if ($data['type'] == 'shipping') {
        $shippingHtml .= '<label class="label button-height"><input class="mid-margin-right switch tiny" type="checkbox" name="shipping[' . $name . ']" value="1" id="shipping_' . strtolower($name) . '" />' . str_replace('_', ' ', $name) . '</label><br />';
      }
    }
    
    $result['extraFormHtml'] = '<fieldset class="fieldset margin-top">' .
                               '  <legend class="legend">' . $lC_Language->get('legend_checkout_options') . '</legend>' . 
                               '  <div class="columns no-margin-bottom">' . 
                               '    <div class="new-row six-columns twelve-columns-tablet no-margin-bottom">' . 
                               '      <h5 class="no-margin-top">' . $lC_Language->get('heading_payment_methods_available') . lc_show_info_bubble($lC_Language->get('info_bubble_payment_methods_available'), 'margin-left:6px; margin-top:0px', 'on-left grey') . '</h5>' . 
                               '      <p>' . $paymentHtml . '</p>' . 
                               '    </div>' . 
                               '    <div class="six-columns twelve-columns-tablet no-margin-bottom">' . 
                               '      <h5 class="no-margin-top">' . $lC_Language->get('heading_shipping_methods_available') . lc_show_info_bubble($lC_Language->get('info_bubble_shipping_methods_available'), 'margin-left:6px; margin-top:0px', 'on-left grey') . '</h5>' . 
                               '      <p>' . $shippingHtml . '</p>' . 
                               '      <h5 class="no-margin-top">' . $lC_Language->get('heading_taxable_status') . lc_show_info_bubble($lC_Language->get('info_bubble_taxable_status'), 'margin-left:6px; margin-top:0px', 'on-left grey') . '</h5>' . 
                               '      <p><select class="input with-small-padding full-width" name="taxable" id="taxable"><option value="1">' . $lC_Language->get('text_yes') . '</option><option value="0">' . $lC_Language->get('text_no') . '</option></select></p>' . 
                               '    </div>' . 
                               '  </div>' . 
                               '</fieldset>' .                    
                               '<fieldset class="fieldset margin-top">' . 
                               '  <legend class="legend">' . $lC_Language->get('legend_catalog_visibility') . '</legend>' . 
                               '  <div class="columns ">' . 
                               '    <div class="new-row six-columns twelve-columns-tablet no-margin-bottom">' . 
                               '      <h5 class="no-margin-top">' . $lC_Language->get('heading_access_group_visibility') . lc_show_info_bubble($lC_Language->get('info_bubble_access_group_visibility'), 'margin-left:6px; margin-top:0px', 'on-left grey') . '</h5>' . 
                               '      <p>' . $accessHtml . '</p>' . 
                               '    </div>' . 
                               '    <div class="six-columns twelve-columns-tablet no-margin-botoom">' . 
                               '      <h5 class="no-margin-top">' . $lC_Language->get('heading_hidden_product_notification') . lc_show_info_bubble($lC_Language->get('info_bubble_hidden_product_notification'), 'margin-left:6px; margin-top:0px', 'on-left grey') . '</h5>' . 
                               '      <div class="small-margin-left">' . 
                               '        <input id="hidden_products_notification" name="hidden_products_notification" type="checkbox" class="switch wider" data-text-off="' . $lC_Language->get('slider_switch_disabled') . '" data-text-on="' . $lC_Language->get('slider_switch_enabled') . '"' . (isset($cgInfo) && ($cgInfo->get('hidden_products_notification') == 1) || !isset($_GET['cgid']) ? ' checked' : '') . ' />' . 
                               '      </div>' . 
                               '      <p class="mid-margin-top large-margin-right no-margin-bottom"><small>' . $lC_Language->get('text_hidden_product_notification_info') . '</small></p>' . 
                               '    </div>' . 
                               '  </div>' . 
                               '</fieldset>';

    return $result;
  }  
  
}
?>
