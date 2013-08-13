<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book_details.php v1.0 2013-08-08 datazen $
*/
global $countries_array;
if ($lC_MessageStack->size('address') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('address', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--modules/address_book_details.php start-->
<div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;">
  <span><?php echo $lC_Language->get('form_validation_error'); ?></span>
</div>   
<div class="single-bg">
  <div class="embed-form short-code-column one-half no-margin-bottom">   
    <h3>Personal Details</h3>
    <ul id="personal_details">
      <li><?php echo lc_draw_label('', 'firstname', null, false) . ' ' . lc_draw_input_field('firstname', (isset($Qentry) ? $Qentry->value('entry_firstname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getFirstName() : null)), 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" class="txt" style="width:99%;"'); ?></li>
      <li><?php echo lc_draw_label('', 'lastname', null, false) . ' ' . lc_draw_input_field('lastname', (isset($Qentry) ? $Qentry->value('entry_lastname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getLastName() : null)), 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" class="txt" style="width:99%;"'); ?></li>
      <?php
      if (ACCOUNT_GENDER > -1) {
        $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')), array('id' => 'f', 'text' => $lC_Language->get('gender_female')));   
        echo '<li style="font-size:.9em; margin:14px 0 15px 3px;">' . lc_draw_label('', 'gender', (isset($Qentry) ? $Qentry->value('entry_gender') : null), false) . ' ' . lc_draw_radio_field('gender', $gender_array, 'm', 'style="height:12px;"') . '</li>'; 
      }
      if (ACCOUNT_TELEPHONE > -1) {
        echo '<li>' . lc_draw_label('', 'telephone', null, '', (ACCOUNT_TELEPHONE > 0)) . ' ' . lc_draw_input_field('telephone', (isset($Qentry) ? $Qentry->value('entry_telephone') : null), 'placeholder="' . $lC_Language->get('field_customer_telephone_number') . '" class="txt ' . ((ACCOUNT_TELEPHONE > 0) ? 'required' : null) . '" style="width:99%;"') . '</li>';
      }
      if (ACCOUNT_FAX > -1) {
        echo '<li>' . lc_draw_label('', 'fax', null, '', (ACCOUNT_FAX > 0)) . ' ' . lc_draw_input_field('fax', (isset($Qentry) ? $Qentry->value('entry_fax') : null), 'placeholder="' . $lC_Language->get('field_customer_fax_number') . '" class="txt ' . ((ACCOUNT_FAX > 0) ? 'required' : null) . '" style="width:99%;"') . '</li>';
      }
      ?>      
    </ul>     
  </div>
  <div class="embed-form short-code-column one-half column-last no-margin-bottom">
    <h3>Address</h3>
    <ul id="address_details">
      <?php
        if (ACCOUNT_COMPANY > -1) {
          echo '<li>' . lc_draw_label('', null, 'company', (ACCOUNT_COMPANY > 0)) . ' ' . lc_draw_input_field('company', (isset($Qentry) ? $Qentry->value('entry_company') : null), 'placeholder="' . $lC_Language->get('field_customer_company') . '" class="txt ' . ((ACCOUNT_COMPANY > 0) ? 'required' : null) . '" style="width:99%;"') . '</li>';
        }
        echo '<li>' . lc_draw_label('', null, 'street_address') . ' ' . lc_draw_input_field('street_address', (isset($Qentry) ? $Qentry->value('entry_street_address') : null), 'placeholder="' . $lC_Language->get('field_customer_street_address') . '" class="txt" style="width:99%;"') . '</li>';
        if (ACCOUNT_SUBURB > -1) {
          echo '<li>' . lc_draw_label('', null, 'suburb', (ACCOUNT_SUBURB > 0)) . ' ' . lc_draw_input_field('suburb', (isset($Qentry) ? $Qentry->value('entry_suburb') : null), 'placeholder="' . $lC_Language->get('field_customer_suburb') . '" class="txt ' . ((ACCOUNT_SUBURB > 0) ? 'required' : null) . '" style="width:99%;"') . '</li>';
        }
        echo '<li>' . lc_draw_label('', 'city', null, '', true) . ' ' . lc_draw_input_field('city', (isset($Qentry) ? $Qentry->value('entry_city') : null), 'placeholder="' . $lC_Language->get('field_customer_city') . '" class="txt" style="width:99%;"') . '</li>';

        if (ACCOUNT_STATE > -1) {
        ?>
        <li>
          <div id="uniform-zones" class="selector">   
          </div>
        </li>
        <?php
        }
      ?>
      <li>
          <?php 
            echo lc_draw_label(null, null, 'country') . lc_draw_pull_down_menu('country', $countries_array, (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY), 'onchange="getZonesDropdown(this.value)" style="padding-top:6px;"');
          ?>
     </li>
      <?php
        if (ACCOUNT_POST_CODE > -1) {
          echo '<li>' . lc_draw_label('', null, 'postcode', (ACCOUNT_POST_CODE > 0)) . ' ' . lc_draw_input_field('postcode', (isset($Qentry) ? $Qentry->value('entry_postcode') : null), 'placeholder="' . $lC_Language->get('field_customer_post_code') . '" class="txt ' . ((ACCOUNT_POST_CODE > 0) ? 'required' : null) . '" style="width:99%;"') . '</li>';
        }
      ?>
    </ul>    
  </div>
</div>
<!--modules/address_book_details.php end-->