<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 lC_ommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book_details.php v1.0 2013-08-08 datazen $
*/ 
?>
<!--content/account/address_book_details.php start-->
<div class="col-sm-6 col-lg-6 large-padding-left margin-top">
  <div class="well no-padding-top">
    <h3><?php echo $lC_Language->get('personal_details_title'); ?></h3>
      <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo lc_draw_input_field('firstname', (isset($Qentry) ? $Qentry->value('entry_firstname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getFirstName() : null)), 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" class="form-control"'); ?></div>
      <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo lc_draw_input_field('lastname', (isset($Qentry) ? $Qentry->value('entry_lastname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getLastName() : null)), 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" class="form-control"'); ?></div>
      <?php
      if (ACCOUNT_GENDER > -1) {
        ?>
        <div class="form-group no-wrap no-margin-top margin-bottom full-width small-margin-left-neg">
          <label class="normal"></label>
          <select class="form-control" name="gender" id="gender">
            <option value="m" <?php echo (isset($Qentry) && $Qentry->value('entry_gender') == 'm') ? 'selected="selected"' : (isset($_GET['new'])) ? 'selected="selected"' : null; ?>><?php echo $lC_Language->get('gender_male'); ?></option>
            <option value="f" <?php echo (isset($Qentry) && $Qentry->value('entry_gender') == 'f') ? 'selected="selected"' : null; ?>><?php echo $lC_Language->get('gender_female'); ?></option>
          </select>
        </div>
        <?php
      }
      if (ACCOUNT_TELEPHONE > -1) {
         echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('telephone', (isset($Qentry) ? $Qentry->value('entry_telephone') : null), 'placeholder="' . $lC_Language->get('field_customer_telephone_number') . '" class="form-control"') . '</div>' . "\n";
      }
      if (ACCOUNT_FAX > -1) {
        echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('fax', (isset($Qentry) ? $Qentry->value('entry_fax') : null), 'placeholder="' . $lC_Language->get('field_customer_fax_number') . '" class="form-control"') . '</div>' . "\n";
      }
      ?>
  </div>
</div>
<div class="col-sm-6 col-lg-6 margin-top clearfix">
  <div class="well no-padding-top">
    <h3><?php echo $lC_Language->get('address_title'); ?></h3>
    <?php
    if (ACCOUNT_COMPANY > -1) {
      echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('company', (isset($Qentry) ? $Qentry->value('entry_company') : null), 'placeholder="' . $lC_Language->get('field_customer_company') . '" class="form-control"') . '</div>';
    }
    echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('street_address', (isset($Qentry) ? $Qentry->value('entry_street_address') : null), 'placeholder="' . $lC_Language->get('field_customer_street_address') . '" class="form-control"') . '</div>';
    if (ACCOUNT_SUBURB > -1) {
      echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('suburb', (isset($Qentry) ? $Qentry->value('entry_suburb') : null), 'placeholder="' . $lC_Language->get('field_customer_suburb') . '" class="form-control"') . '</div>';
    }
    echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('city', (isset($Qentry) ? $Qentry->value('entry_city') : null), 'placeholder="' . $lC_Language->get('field_customer_city') . '" class="form-control"') . '</div>';
    if (ACCOUNT_STATE > -1) {
      echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label><span class="address-book-state-container">';
      
      if ( (isset($_GET['new']) && ($_GET['new'] == 'save')) || (isset($_GET['edit']) && ($_GET['edit'] == 'save')) || (isset($_GET['address_book']) && ($_GET['address_book'] == 'process')) ) {
        if ($entry_state_has_zones === true) {
          $Qzones = $lC_Database->query('select zone_name from :table_zones where zone_country_id = :zone_country_id order by zone_name');
          $Qzones->bindTable(':table_zones', TABLE_ZONES);
          $Qzones->bindInt(':zone_country_id', $_POST['country']);
          $Qzones->execute();

          $zones_array = array();
          while ($Qzones->next()) {
            $zones_array[] = array('id' => $Qzones->value('zone_name'), 'text' => $Qzones->value('zone_name'));
          }

           echo lc_draw_pull_down_menu('state', $zones_array, null, 'class="form-control"');
        } else {
          echo lc_draw_input_field('state', null, 'placeholder="' . $lC_Language->get('field_customer_state') . '" class="form-control"');
        }
      } else {
        if (isset($Qentry)) {
          $zone = $Qentry->value('entry_state');

          if ($Qentry->valueInt('entry_zone_id') > 0) {
            $zone = lC_Address::getZoneName($Qentry->valueInt('entry_zone_id'));
          }
        }

        echo lc_draw_input_field('state', (isset($Qentry) ? $zone : null), 'placeholder="' . $lC_Language->get('field_customer_state') . '" class="form-control"');
      }      
      echo '</span></div>';
    }
       
    if (ACCOUNT_POST_CODE > -1) {
      echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_input_field('postcode', (isset($Qentry) ? $Qentry->value('entry_postcode') : null), 'placeholder="' . $lC_Language->get('field_customer_post_code') . '" class="form-control"') . '</div>';
    }                
    
    $countries_array = array(array('id' => '',
                                   'text' => $lC_Language->get('pull_down_default')));

    foreach (lC_Address::getCountries() as $country) {
      $countries_array[] = array('id' => $country['id'],
                                 'text' => $country['name']);
    }
    echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label>' . lc_draw_pull_down_menu('country', $countries_array, (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY), 'class="form-control"') . '</div>';
    
    if ($lC_Customer->hasDefaultAddress() && ((isset($_GET['edit']) && ($lC_Customer->getDefaultAddressID() != $_GET['address_book'])) || isset($_GET['new'])) ) {
      echo '<div class="checkbox small-margin-left">' . lc_draw_checkbox_field('primary', null, null, null, null) . '<label class="small-margin-left">' . $lC_Language->get('set_as_primary') . '</label></div>';
    }
    ?>
  </div>
</div>
<?php $lC_Template->addJavascriptPhpFilename('templates/bs_starter/javascript/form_check.js.php'); ?>
<script>
$(document).ready(function() {
  $('#country').change(function() {
    $('.address-book-state-container').html('<?php echo lc_draw_input_field('state', null, 'placeholder="' . $lC_Language->get('field_customer_state') . '" class="form-control"'); ?>');  
  });
});
</script>
<!--content/account/address_book_details.php end-->