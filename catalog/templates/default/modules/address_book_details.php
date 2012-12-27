<?php
/*
  $Id: address_book_details.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<div class="short-code-column">
    <ol>
    <?php
      if (ACCOUNT_GENDER > -1) {
        $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
                              array('id' => 'f', 'text' => $lC_Language->get('gender_female')));
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_gender'), null, 'fake', (ACCOUNT_GENDER > 0)) . ' ' . lc_draw_radio_field('gender', $gender_array, (isset($Qentry) ? $Qentry->value('entry_gender') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getGender() : null))); ?></li>
    <?php
      }
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_first_name'), null, 'firstname', true) . ' ' . lc_draw_input_field('firstname', (isset($Qentry) ? $Qentry->value('entry_firstname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getFirstName() : null))); ?></li>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_last_name'), null, 'lastname', true) . ' ' . lc_draw_input_field('lastname', (isset($Qentry) ? $Qentry->value('entry_lastname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getLastName() : null))); ?></li>
    <?php
      if (ACCOUNT_COMPANY > -1) {
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_company'), null, 'company', (ACCOUNT_COMPANY > 0)) . ' ' . lc_draw_input_field('company', (isset($Qentry) ? $Qentry->value('entry_company') : null)); ?></li>
    <?php
      }
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_street_address'), null, 'street_address', true) . ' ' . lc_draw_input_field('street_address', (isset($Qentry) ? $Qentry->value('entry_street_address') : null)); ?></li>
    <?php
      if (ACCOUNT_SUBURB > -1) {
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_suburb'), null, 'suburb', (ACCOUNT_SUBURB > 0)) . ' ' . lc_draw_input_field('suburb', (isset($Qentry) ? $Qentry->value('entry_suburb') : null)); ?></li>
    <?php
      }
      if (ACCOUNT_POST_CODE > -1) {
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_post_code'), null, 'postcode', (ACCOUNT_POST_CODE > 0)) . ' ' . lc_draw_input_field('postcode', (isset($Qentry) ? $Qentry->value('entry_postcode') : null)); ?></li>
    <?php
      }
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_city'), null, 'city', true) . ' ' . lc_draw_input_field('city', (isset($Qentry) ? $Qentry->value('entry_city') : null)); ?></li>
    <?php
      if (ACCOUNT_STATE > -1) {
    ?>
      <li>
    <?php 
      echo lc_draw_label($lC_Language->get('field_customer_state'), null, 'state', (ACCOUNT_STATE > 0)) . ' ';
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
          echo lc_draw_pull_down_menu('state', $zones_array);
        } else {
          echo lc_draw_input_field('state');
        }
      } else {
        if (isset($Qentry)) {
          $zone = $Qentry->value('entry_state');
          if ($Qentry->valueInt('entry_zone_id') > 0) {
            $zone = lC_Address::getZoneName($Qentry->valueInt('entry_zone_id'));
          }
        }
        echo lc_draw_input_field('state', (isset($Qentry) ? $zone : null));
      } 
    ?>
      </li>
    <?php
      }
    ?>
      <li>
    <?php 
      echo lc_draw_label($lC_Language->get('field_customer_country'), null, 'country', true) . ' ';
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
      if (ACCOUNT_TELEPHONE > -1) {
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_telephone_number'), null, 'telephone', (ACCOUNT_TELEPHONE > 0)) . ' ' . lc_draw_input_field('telephone', (isset($Qentry) ? $Qentry->value('entry_telephone') : null)); ?></li>
    <?php
      }
      if (ACCOUNT_FAX > -1) {
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('field_customer_fax_number'), null, 'fax', (ACCOUNT_FAX > 0)) . ' ' . lc_draw_input_field('fax', (isset($Qentry) ? $Qentry->value('entry_fax') : null)); ?></li>
    <?php
      }
      if ($lC_Customer->hasDefaultAddress() && ((isset($_GET['edit']) && ($lC_Customer->getDefaultAddressID() != $_GET['address_book'])) || isset($_GET['new'])) ) {
    ?>
      <li><?php echo lc_draw_label($lC_Language->get('set_as_primary'), null) . ' ' . lc_draw_checkbox_field('primary'); ?></li>
    <?php
      }
    ?>
    </ol>
</div>
<div style="clear:both;"></div>