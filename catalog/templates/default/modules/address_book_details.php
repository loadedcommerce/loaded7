<?php
/*
  $Id: address_book_details.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $countries_array;

if ($lC_MessageStack->size('address') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('address', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<style>
#personal_details li { margin: 10px 0; }
#address_details li { margin: 10px 0; }
.embed-form input { height: 26px; padding-left:4px; }

#uniform-country.selector { height: 22px; padding: 3px 3px 3px 10px; width: 96%; margin: 0 0 10px 0; }

#uniform-country select {
    color: #818181;
    cursor: pointer;
    font-size: 12px;
    height: 22px;
    left: 0;
    position: absolute;
    top: 0;
    width: 98%;
}
#uniform-country select option {
    border: medium none;
    padding: 5px;
}
#uniform-country span {
    color: #000;
    cursor: pointer;
    font-size: 12px;
    height: 22px;
    line-height: 22px;
    position: absolute;
    right: 0;
    top: 4px;
    width: 99%;
}

</style>
<!--EDIT ADDRESS BOOK SECTION STARTS-->

  <div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;">
    <span><?php echo $lC_Language->get('form_validation_error'); ?></span>
  </div>   
  <div class="single-bg">
    <div class="embed-form short-code-column one-half no-margin-bottom">   
      <h3>Personal Details</h3>
      <ul id="personal_details">
        <li><?php echo lc_draw_label('', 'firstname', null, false) . ' ' . lc_draw_input_field('firstname', (isset($Qentry) ? $Qentry->value('entry_firstname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getFirstName() : null)), 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_first_name') . '\'" class="txt" style="width:99%;"'); ?></li>
        <li><?php echo lc_draw_label('', 'lastname', null, false) . ' ' . lc_draw_input_field('lastname', (isset($Qentry) ? $Qentry->value('entry_lastname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getLastName() : null)), 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_last_name') . '\'" class="txt" style="width:99%;"'); ?></li>
        <?php
        if (ACCOUNT_DATE_OF_BIRTH == '1') {
          //echo '<li>' . lc_draw_label($lC_Language->get('field_customer_date_of_birth'), 'dob_days', null, true) . lc_draw_date_pull_down_menu('dob', null, false, null, null, @date('Y')-1901, -5) . '</li>';
          echo '<li>' . lc_draw_label('', 'dob_days', null, false) . ' ' . lc_draw_input_field('dob', (isset($Qentry) ? $Qentry->value('entry_dob') : null), 'placeholder="' . $lC_Language->get('field_customer_date_of_birth') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_date_of_birth') . '\'" class="txt required date" style="width:86%;"') . '</li>'; 
        }
        if (ACCOUNT_GENDER > -1) {
          $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')), array('id' => 'f', 'text' => $lC_Language->get('gender_female')));   
          echo '<li style="font-size:.9em; margin:14px 0 15px 3px;">' . lc_draw_label('', 'gender', (isset($Qentry) ? $Qentry->value('entry_gender') : null), false) . ' ' . lc_draw_radio_field('gender', $gender_array, 'm', 'style="height:12px;"') . '</li>'; 
        }
        if (ACCOUNT_TELEPHONE > -1) {
          echo '<li>' . lc_draw_label('', 'telephone', null, '', (ACCOUNT_TELEPHONE > 0)) . ' ' . lc_draw_input_field('telephone', (isset($Qentry) ? $Qentry->value('entry_telephone') : null), 'placeholder="' . $lC_Language->get('field_customer_telephone_number') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_telephone_number') . '\'" class="txt" style="width:99%;"') . '</li>';
        }
        if (ACCOUNT_FAX > -1) {
          echo '<li>' . lc_draw_label('', 'fax', null, '', (ACCOUNT_FAX > 0)) . ' ' . lc_draw_input_field('fax', (isset($Qentry) ? $Qentry->value('entry_fax') : null), 'placeholder="' . $lC_Language->get('field_customer_fax_number') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_fax_number') . '\'" class="txt" style="width:99%;"') . '</li>';
        }
        ?>      
      </ul>     
    </div>
    <div class="embed-form short-code-column one-half column-last no-margin-bottom">
      <h3>Address</h3>
      <ul id="address_details">
        <?php
          if (ACCOUNT_COMPANY > -1) {
            echo '<li>' . lc_draw_label('', null, 'company', (ACCOUNT_COMPANY > 0)) . ' ' . lc_draw_input_field('company', (isset($Qentry) ? $Qentry->value('entry_company') : null), 'placeholder="' . $lC_Language->get('field_customer_company') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_company') . '\'" class="txt" style="width:99%;"') . '</li>';
          }
          echo '<li>' . lc_draw_label('', null, 'street_address') . ' ' . lc_draw_input_field('street_address', (isset($Qentry) ? $Qentry->value('entry_street_address') : null), 'placeholder="' . $lC_Language->get('field_customer_street_address') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_street_address') . '\'" class="txt" style="width:99%;"') . '</li>';
          if (ACCOUNT_SUBURB > -1) {
            echo '<li>' . lc_draw_label('', null, 'suburb', (ACCOUNT_SUBURB > 0)) . ' ' . lc_draw_input_field('suburb', (isset($Qentry) ? $Qentry->value('entry_suburb') : null), 'placeholder="' . $lC_Language->get('field_customer_suburb') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_suburb') . '\'" class="txt" style="width:99%;"') . '</li>';
          }
          echo '<li>' . lc_draw_label('', 'city', null, '', true) . ' ' . lc_draw_input_field('city', (isset($Qentry) ? $Qentry->value('entry_city') : null), 'placeholder="' . $lC_Language->get('field_customer_city') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_city') . '\'" class="txt" style="width:99%;"') . '</li>';

          if (ACCOUNT_STATE > -1) {
          ?>
          <li>
            <span id="zonesContainer"></span>
          </li>
          <?php
          }
        ?>
        <li>
          <?php 
            echo lc_draw_label(null, null, 'country') . lc_draw_pull_down_menu('country', $countries_array, (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY));
          ?>
        </li>
        <?php
          if (ACCOUNT_POST_CODE > -1) {
            echo '<li>' . lc_draw_label('', null, 'postcode', (ACCOUNT_POST_CODE > 0)) . ' ' . lc_draw_input_field('postcode', (isset($Qentry) ? $Qentry->value('entry_postcode') : null), 'placeholder="' . $lC_Language->get('field_customer_post_code') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_post_code') . '\'" class="txt" style="width:99%;"') . '</li>';
          }
        ?>

      </ul>    

    </div>
  </div>
   
<!--EDIT ADDRESS BOOK SECTION ENDS-->



<?php /*
<style>
  #personal_details li { margin: 10px 0; }
  #address_details li { margin: 10px 0; }
  #address_details li input { height: 26px; padding-left:4px; }
  #address_details li select {  width: 240px; height:26px; }
</style>
<!--CREATE ACCOUNT SECTION STARTS-->
<div id="accountCreate" class="full_page">
  <div class="single-bg">
    <div class="short-code-column one-half no-margin-bottom">   
      <h3>Personal Details</h3>
      <ul id="personal_details">
        <li><?php echo lc_draw_label('', 'firstname', null, false) . ' ' . lc_draw_input_field('firstname', (isset($Qentry) ? $Qentry->value('entry_firstname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getFirstName() : null)), 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_first_name') . '\'" class="txt" style="width:99%;"'); ?></li>
        <li><?php echo lc_draw_label('', 'lastname', null, false) . ' ' . lc_draw_input_field('lastname', (isset($Qentry) ? $Qentry->value('entry_lastname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getLastName() : null)), 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_last_name') . '\'" class="txt" style="width:99%;"'); ?></li>
        <?php
          if (ACCOUNT_GENDER > -1) {
            $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
              array('id' => 'f', 'text' => $lC_Language->get('gender_female')));   
            echo '<li style="font-size:.9em; margin-left:3px;">' . lc_draw_label('', 'gender', null, false) . ' ' . lc_draw_radio_field('gender', $gender_array, (isset($Qentry) ? $Qentry->value('entry_gender') : null), 'style="height:12px;"') . '</li>'; 
          }  
          if (ACCOUNT_TELEPHONE > -1) {
          ?>
          <li><?php echo lc_draw_label('', 'telephone', null, '', (ACCOUNT_TELEPHONE > 0)) . ' ' . lc_draw_input_field('telephone', (isset($Qentry) ? $Qentry->value('entry_telephone') : null), 'placeholder="' . $lC_Language->get('field_customer_telephone_number') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_telephone_number') . '\'" class="txt" style="width:99%;"'); ?></li>
          <?php
          }
          if (ACCOUNT_FAX > -1) {
          ?>
          <li><?php echo lc_draw_label('', 'fax', null, '', (ACCOUNT_FAX > 0)) . ' ' . lc_draw_input_field('fax', (isset($Qentry) ? $Qentry->value('entry_fax') : null), 'placeholder="' . $lC_Language->get('field_customer_fax_number') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_fax_number') . '\'" class="txt" style="width:99%;"'); ?></li>
          <?php
          }
        ?>      
      </ul>     
    </div>
    
    <div class="short-code-column one-half column-last no-margin-bottom">
      <h3>Address</h3>
      <ul id="address_details">
        <?php
          if (ACCOUNT_COMPANY > -1) {
          ?>
          <li><?php echo lc_draw_label('', null, 'company', (ACCOUNT_COMPANY > 0)) . ' ' . lc_draw_input_field('company', (isset($Qentry) ? $Qentry->value('entry_company') : null), 'placeholder="' . $lC_Language->get('field_customer_company') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_company') . '\'" class="txt" style="width:99%;"'); ?></li>
          <?php
          }
        ?>
        <li><?php echo lc_draw_label('', null, 'street_address') . ' ' . lc_draw_input_field('street_address', (isset($Qentry) ? $Qentry->value('entry_street_address') : null), 'placeholder="' . $lC_Language->get('field_customer_street_address') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_street_address') . '\'" class="txt" style="width:99%;"'); ?></li>
        <?php
          if (ACCOUNT_SUBURB > -1) {
          ?>
          <li><?php echo lc_draw_label('', null, 'suburb', (ACCOUNT_SUBURB > 0)) . ' ' . lc_draw_input_field('suburb', (isset($Qentry) ? $Qentry->value('entry_suburb') : null), 'placeholder="' . $lC_Language->get('field_customer_suburb') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_suburb') . '\'" class="txt" style="width:99%;"'); ?></li>
          <?php
          }
        ?>
        <li><?php echo lc_draw_label('', 'city', null, '', true) . ' ' . lc_draw_input_field('city', (isset($Qentry) ? $Qentry->value('entry_city') : null), 'placeholder="' . $lC_Language->get('field_customer_city') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_city') . '\'" class="txt" style="width:99%;"'); ?></li>

        <?php
          if (ACCOUNT_STATE > -1) {

          ?>
          <li>
            <?php 
              if ( (isset($_GET['new']) && ($_GET['new'] == 'save')) || (isset($_GET['edit']) && ($_GET['edit'] == 'save')) || (isset($_GET[$lC_Template->getModule()]) && ($_GET[$lC_Template->getModule()] == 'process')) ) {
                if ($entry_state_has_zones === true) {
                  $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
                  $Qzones->bindTable(':table_zones', TABLE_ZONES);
                  $Qzones->bindInt(':zone_country_id', $_POST['country']);
                  $Qzones->execute();

                  $zones_array = array();
                  while ($Qzones->next()) {
                    $zones_array[] = array('id' => $Qzones->value('zone_name'), 'text' => $Qzones->value('zone_name'));
                  }
                  echo lc_draw_label($lC_Language->get('field_customer_state'), null, 'state', (ACCOUNT_STATE > 0)) . ' ';
                  echo lc_draw_pull_down_menu('state', $zones_array);
                } else {
                  echo lc_draw_label('', null, 'state') . ' ' . lc_draw_input_field('state', (isset($Qentry) ? $Qentry->value('zone_name') : null), 'placeholder="' . $lC_Language->get('field_customer_state') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_state') . '\'" class="txt" style="width:99%;"');
                }
              } else {
                if (isset($Qentry)) {
                  $zone = $Qentry->value('entry_state');
                  if ($Qentry->valueInt('entry_zone_id') > 0) {
                    $zone = lC_Address::getZoneName($Qentry->valueInt('entry_zone_id'));
                  }
                }
                echo lc_draw_label('', null, 'state') . ' ' . lc_draw_input_field('state', (isset($Qentry) ? $zone : null), 'placeholder="' . $lC_Language->get('field_customer_state') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_state') . '\'" class="txt" style="width:99%;"');
              } 
            ?>
          </li>
          <?php
          }
        ?>
        <li style="font-size:.9em; margin-left:5px;">
          <?php 
            echo lc_draw_label($lC_Language->get('field_customer_country'), null, 'country') . '<br /> ';
            $countries_array = array(array('id' => '',
                'text' => $lC_Language->get('pull_down_default')));
            foreach (lC_Address::getCountries() as $country) {
              $countries_array[] = array('id' => $country['id'],
                'text' => $country['name']);
            }
            echo lc_draw_pull_down_menu('country', $countries_array, (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY));
          ?>
        </li>
        <?php
          if (ACCOUNT_POST_CODE > -1) {
          ?>
          <li><?php echo lc_draw_label('', null, 'postcode', (ACCOUNT_POST_CODE > 0)) . ' ' . lc_draw_input_field('postcode', (isset($Qentry) ? $Qentry->value('entry_postcode') : null), 'placeholder="' . $lC_Language->get('field_customer_post_code') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_post_code') . '\'" class="txt" style="width:99%;"'); ?></li>
          <?php
          }
        ?>

      </ul>
    </div>
    
    
  </div>
</div>
*/ ?>

<div style="clear:both;"></div>