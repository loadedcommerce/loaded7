<?php
/**  
*  $Id: account_edit.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
$Qaccount = lC_Account::getEntry();
if ($lC_MessageStack->size('account_edit') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('account_edit', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/account/account_edit.php start-->
<div class="full_page">
  <form name="account_edit" id="account_edit" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'edit=save', 'SSL'); ?>" method="post">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;"> <span><?php echo $lC_Language->get('form_validation_error'); ?></span> </div>
      <div class="single-bg">
        <div class="embed-form short-code-column one-half no-margin-bottom">
          <h3>Personal Details</h3>
          <ul id="personal_details">
            <li><?php echo lc_draw_label('', 'firstname', null, false) . ' ' . lc_draw_input_field('firstname', $Qaccount->value('customers_firstname') , 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" class="txt" style="width:99%;"'); ?></li>
            <li><?php echo lc_draw_label('', 'lastname', null, false) . ' ' . lc_draw_input_field('lastname', $Qaccount->value('customers_lastname'), 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" class="txt" style="width:99%;"'); ?></li>
            <?php
              if (ACCOUNT_DATE_OF_BIRTH == '1') {
                echo '<li>' . lc_draw_label('', 'dob_days', null, false) . ' ' . lc_draw_input_field('dob', $Qaccount->value('customers_dob_month') . '/' . $Qaccount->value('customers_dob_date') . '/' . $Qaccount->value('customers_dob_year'),'placeholder="' . $lC_Language->get('field_customer_date_of_birth') . '" class="txt required date" style="width:86%;"'); 
              } 
              if (ACCOUNT_GENDER > -1) {
                $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
                  array('id' => 'f', 'text' => $lC_Language->get('gender_female')));
                echo '<li style="font-size:.9em; margin:14px 0 15px 3px;">' . lc_draw_label('', 'fake', null, false) . lc_draw_radio_field('gender', $gender_array, $Qaccount->value('customers_gender'), 'style="height:12px;"') . '</li>'; 
              }
            ?>
          </ul>
        </div>
        <div class="embed-form short-code-column one-half column-last no-margin-bottom">
          <h3>Login Details</h3>
          <ul id="address_details">
            <?php
              echo '<li>' . lc_draw_label('', 'email_address', null, '', false) . ' ' . lc_draw_input_field('email_address', $Qaccount->value('customers_email_address'), 'placeholder="' . $lC_Language->get('field_customer_email_address') . '" class="txt" style="width:99%;"') . '</li>';      
            ?>
          </ul>
        </div>
      </div>
      <div style="clear:both;"></div>
      <div id="accountEditActions" class="action_buttonbar"  style="margin-top:10px;"> 
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span> 
        <span class="buttonRight"><button class="button purple_btn" type="submit" onclick="validateForm();"><?php echo $lC_Language->get('button_update'); ?></button></span> 
      </div>
      <div style="clear:both;"></div>
    </div>
  </form>
</div>
<!--content/account/account_edit.php end-->