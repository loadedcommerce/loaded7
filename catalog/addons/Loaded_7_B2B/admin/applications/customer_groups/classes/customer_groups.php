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

if (!defined('DIR_FS_ADMIN')) return false;

require_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/customer_groups/classes/customer_groups.php'));

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
    $levels = array();
    if (is_array($result['cgData'])) {
      $levels = explode(';', $result['cgData']['customers_access_levels']);
    }

    while ($Qlevels->next()) {  
      $checked = ( (in_array($Qlevels->valueInt('id'), $levels)) ? ' checked="checked" ' : '' );
      $accessHtml .= '<label class="label button-height"><input class="mid-margin-right switch tiny" type="checkbox" name="level[' . $Qlevels->valueInt('id') . ']" value="1" id="customer_access_level_' . $Qlevels->valueInt('id') . '"' . $checked . ' />' . $Qlevels->value('level') . '</label><br />';
    }

    $Qlevels->freeResult();
    
    // get the payment/shipping addons for the group
    $paymentHtml = '';
    $shippingHtml = '';   
    foreach($lC_Addons->getAddons('enabled') as $name => $data) {
      if ($data['type'] == 'payment' || $data['type'] == 'paypal') {
        $checked = (strstr($result['cgData']['payment_modules'], $name)) ? ' checked="checked" ' : '';
        $paymentHtml .= '<label class="label button-height"><input class="mid-margin-right switch tiny" type="checkbox" name="payment[' . $name . ']" value="1" id="payment_' . strtolower($name) . '"' . $checked . ' />' . str_replace('_', ' ', $name) . '</label><br />';
      } else if ($data['type'] == 'shipping') {
        $checked = (strstr($result['cgData']['shipping_modules'], $name)) ? ' checked="checked" ' : '';
        $shippingHtml .= '<label class="label button-height"><input class="mid-margin-right switch tiny" type="checkbox" name="shipping[' . $name . ']" value="1" id="shipping_' . strtolower($name) . '"' . $checked . ' />' . str_replace('_', ' ', $name) . '</label><br />';
      }
    }
    $taxStatus = (isset($result['cgData']['taxable']) && $result['cgData']['taxable'] == 1) ? ' checked="checked" ' : '';
    $hpnChecked = (isset($result['cgData']['hidden_products_notification']) && $result['cgData']['hidden_products_notification'] == 1) ? ' checked="checked" ' : '';
    if ($hpnChecked == '' && !isset($_GET['cgid'])) $hpnChecked = ' checked="checked" ';
    
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
                               '      <p><select class="input with-small-padding full-width" name="taxable" id="taxable"><option value="1" ' . (($result['cgData']['taxable'] == 1) ? ' selected="selected"' : '') . '>' . $lC_Language->get('text_yes') . '</option><option value="0" ' . (($result['cgData']['taxable'] == 0) ? ' selected="selected"' : '') . '>' . $lC_Language->get('text_no') . '</option></select></p>' . 
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
                               '        <input id="hidden_products_notification" name="hidden_products_notification" type="checkbox" class="switch wider" data-text-off="' . $lC_Language->get('slider_switch_disabled') . '" data-text-on="' . $lC_Language->get('slider_switch_enabled') . '"' . $hpnChecked . ' />' . 
                               '      </div>' . 
                               '      <p class="mid-margin-top large-margin-right no-margin-bottom"><small>' . $lC_Language->get('text_hidden_product_notification_info') . '</small></p>' . 
                               '    </div>' . 
                               '  </div>' . 
                               '</fieldset>';

    return $result;
  }  
  
  public static function getCustomerAccessLevelsHtml($section = 'customer_groups') {
    global $lC_Database, $lC_Language, $pInfo, $cInfo;
      
    $lC_Language->loadIniFile('customer_groups.php');

    $levels = array();
    if (isset($pInfo)) {
      $levels = explode(';', $pInfo->get('access_levels'));
    } else if (isset($cInfo)) {
      $levels = explode(';', $cInfo['access_levels']);
    }  

    // get the access levels
    $Qlevels = $lC_Database->query('select * from :table_customers_access where status = :status');
    $Qlevels->bindTable(':table_customers_access', TABLE_CUSTOMERS_ACCESS);
    $Qlevels->bindInt(':status', 1);
    $Qlevels->execute();
    
    $accessHtml = '<label class="label button-height small-margin-left"><input onclick="checkAllLevels(this);" class="mid-margin-right input" type="checkbox" name="check_all_levels" id="check_all_levels">' . $lC_Language->get('text_all') . '</label><br />';
    
    while ($Qlevels->next()) {  
      $checked = ( (in_array($Qlevels->valueInt('id'), $levels)) ? ' checked="checked" ' : '' );
      $accessHtml .= '<label class="label button-height margin-left"><input class="mid-margin-right input levels" type="checkbox" name="access_levels[' . $Qlevels->valueInt('id') . ']" value="1" id="access_levels_' . $Qlevels->valueInt('id') . '"' . $checked . ' />' . $Qlevels->value('level') . '</label><br />';
    }

    $Qlevels->freeResult();   
    
    if ($section == 'categories') {
      $accessHtml .= self::_getSyncHtml();
    } 
    
    return $accessHtml;
  }
  
  protected static function _getSyncHtml() {
    global $lC_Language;
    
    $lC_Language->loadIniFile('categories.php');
    
    $html = '<style> .field-block + .field-block { border:none; } .field-block .label, .field-drop .label { float: left; margin: 0 0 0 -200px; text-align: left; width: 180px; } </style>' .                   
            '<div class="button-height field-block">' .
            '  <label for="sync_all_products" class="label"><b>' . $lC_Language->get('text_sync_all_products') . '</b></label>' .
            '  <input id="sync_all_products" name="sync_all_products" type="checkbox" class="switch wide" checked="checked" data-text-off="' . $lC_Language->get('text_no_sync') . '" data-text-on="' . $lC_Language->get('text_sync') . '" />' . lc_show_info_bubble($lC_Language->get('info_bubble_sync_products'), null, 'info-spot on-left grey margin-left margin-right') . 
            '</div>' .                   
            '<div class="button-height field-block no-margin-top">' .
            '  <label for="sync_all_children" class="label"><b>' . $lC_Language->get('text_sync_all_children') . '</b></label>' .
            '  <input id="sync_all_children" name="sync_all_children" type="checkbox" class="switch wide" checked="checked" data-text-off="' . $lC_Language->get('text_no_sync') . '" data-text-on="' . $lC_Language->get('text_sync') . '" />' . lc_show_info_bubble($lC_Language->get('info_bubble_sync_children'), null, 'info-spot on-left grey margin-left margin-right') .
            '</div>'; 
            
    return $html;
  }
 /*
  * Save the customer group information
  *
  * @param integer $id The customer group id used on update, null on insert
  * @param array $data An array containing the customer group information
  * @param boolean $default True = set the customer group to be the default
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data, $default = false) {   
    global $lC_Database, $lC_Language;
    
    $customers_group_id = parent::save($id, $data, $default);
    
    $error = false;

    $lC_Database->startTransaction();

    $Qdel = $lC_Database->query('delete from :table_customers_groups_data where customers_group_id = :customers_group_id');
    $Qdel->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qdel->bindInt(':customers_group_id', $customers_group_id);
    $Qdel->execute();
    
    $Qgdata = $lC_Database->query('insert into :table_customers_groups_data (customers_group_id, baseline_discount, customers_access_levels, hidden_products_notification, taxable, payment_modules, shipping_modules) values (:customers_group_id, :baseline_discount, :customers_access_levels, :hidden_products_notification, :taxable, :payment_modules, :shipping_modules)');
    $Qgdata->bindTable(':table_customers_groups_data', TABLE_CUSTOMERS_GROUPS_DATA);
    $Qgdata->bindInt(':customers_group_id', $customers_group_id);
    $Qgdata->bindFloat(':baseline_discount', $data['baseline']);
    $Qgdata->bindValue(':customers_access_levels', self::_arr2str($data['level']));
    $Qgdata->bindInt(':hidden_products_notification', (($data['hidden_products_notification'] == 'on') ? true : false));
    $Qgdata->bindInt(':taxable', $data['taxable']);
    $Qgdata->bindValue(':payment_modules', self::_arr2str($data['payment']));
    $Qgdata->bindValue(':shipping_modules', self::_arr2str($data['shipping']));
    $Qgdata->setLogging($_SESSION['module'], $customers_group_id);
    $Qgdata->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }    
    
    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return $customers_group_id;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }  
 /*
  * format the array to semi-colo separated string
  *
  * @param  array $arr  The array to format
  * @access private
  * @return string
  */
  private static function _arr2str($arr) {
    if (!is_array($arr)) return false;
    $str = '';
    foreach ($arr as $key => $val) {
      $str .= $key . ';';
    }
    $str = substr($str, 0, -1);
    
    return $str;
  }
}
?>