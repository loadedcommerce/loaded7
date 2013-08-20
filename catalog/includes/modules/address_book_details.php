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
?>
<?php
  if (ACCOUNT_GENDER > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_gender'), null, 'fake', (ACCOUNT_GENDER > 0)) . lc_draw_radio_field('gender', array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')), array('id' => 'f', 'text' => $lC_Language->get('gender_female'))), (isset($Qentry) ? $Qentry->value('entry_gender') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getGender() : null))) . '</li>';
  }
  echo '<li>' . lc_draw_label($lC_Language->get('field_customer_first_name'), null, 'firstname', true) . lc_draw_input_field('firstname', (isset($Qentry) ? $Qentry->value('entry_firstname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getFirstName() : null))) . '</li>';
  echo '<li>' . lc_draw_label($lC_Language->get('field_customer_last_name'), null, 'lastname', true) . lc_draw_input_field('lastname', (isset($Qentry) ? $Qentry->value('entry_lastname') : (!$lC_Customer->hasDefaultAddress() ? $lC_Customer->getLastName() : null))) . '</li>';
  if (ACCOUNT_COMPANY > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_company'), null, 'company', (ACCOUNT_COMPANY > 0)) . lc_draw_input_field('company', (isset($Qentry) ? $Qentry->value('entry_company') : null)) . '</li>';
  }
  echo '<li>' . lc_draw_label($lC_Language->get('field_customer_street_address'), null, 'street_address', true) . lc_draw_input_field('street_address', (isset($Qentry) ? $Qentry->value('entry_street_address') : null)) . '</li>';
  if (ACCOUNT_SUBURB > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_suburb'), null, 'suburb', (ACCOUNT_SUBURB > 0)) . lc_draw_input_field('suburb', (isset($Qentry) ? $Qentry->value('entry_suburb') : null)) . '</li>';
  }
  if (ACCOUNT_POST_CODE > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_post_code'), null, 'postcode', (ACCOUNT_POST_CODE > 0)) . lc_draw_input_field('postcode', (isset($Qentry) ? $Qentry->value('entry_postcode') : null)) . '</li>';
  }
  echo '<li>' . lc_draw_label($lC_Language->get('field_customer_city'), null, 'city', true) . lc_draw_input_field('city', (isset($Qentry) ? $Qentry->value('entry_city') : null)) . '</li>';
  if (ACCOUNT_STATE > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_state'), null, 'state', (ACCOUNT_STATE > 0)) . (isset($Qentry) ? lC_AddressBook::getZonesField($Qentry->valueInt('entry_country_id')) : lC_AddressBook::getZonesField(STORE_COUNTRY)) . '</li>';
  }
  echo '<li>' . lc_draw_label($lC_Language->get('field_customer_country'), null, 'country', true) . lc_draw_pull_down_menu('country', lC_AddressBook::getCountriesDropdownArray(), (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY)) . '</li>';
  if (ACCOUNT_TELEPHONE > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_telephone_number'), null, 'telephone', (ACCOUNT_TELEPHONE > 0)) . lc_draw_input_field('telephone', (isset($Qentry) ? $Qentry->value('entry_telephone') : null)) . '</li>';
  }
  if (ACCOUNT_FAX > -1) {
    echo '<li>' . lc_draw_label($lC_Language->get('field_customer_fax_number'), null, 'fax', (ACCOUNT_FAX > 0)) . lc_draw_input_field('fax', (isset($Qentry) ? $Qentry->value('entry_fax') : null)) . '</li>';
  }
  if ($lC_Customer->hasDefaultAddress() && ((isset($_GET['edit']) && ($lC_Customer->getDefaultAddressID() != $_GET['address_book'])) || isset($_GET['new'])) ) {
    echo '<li>' . lc_draw_label($lC_Language->get('set_as_primary'), null)  . lc_draw_checkbox_field('primary') . '</li>';
  }
?>