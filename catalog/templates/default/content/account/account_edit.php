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
  <form name="account_edit" id="account_edit" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'edit=save', 'SSL'); ?>" method="post" onsubmit="return check_form(account_edit);">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div class="borderPadMe">
        <em style="float:right; color:red;"><?php echo $lC_Language->get('form_required_information'); ?></em>
        <div id="accountEditForm">
          <ol>
            <?php
              if (ACCOUNT_GENDER > -1) {
                $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
                  array('id' => 'f', 'text' => $lC_Language->get('gender_female')));
              ?>
              <li><?php echo lc_draw_label($lC_Language->get('field_customer_gender'), 'fake', null, (ACCOUNT_GENDER > 0)) . lc_draw_radio_field('gender', $gender_array, $Qaccount->value('customers_gender')); ?></li>
              <?php
              }
            ?>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_first_name'), 'firstname', null, true) . ' ' . lc_draw_input_field('firstname', $Qaccount->value('customers_firstname')); ?></li>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_last_name'), 'lastname', null, true) . ' ' . lc_draw_input_field('lastname', $Qaccount->value('customers_lastname')); ?></li>
            <?php
              if (ACCOUNT_DATE_OF_BIRTH == '1') {
              ?>
              <li>
                <span style="float:left; padding-right:5px;">
                <?php 
                  echo lc_draw_label($lC_Language->get('field_customer_date_of_birth'), 'dob_days', null, true) . '</span>&nbsp;' . 
                  lc_draw_input_field('dob', $Qaccount->value('customers_dob_month') . '/' . $Qaccount->value('customers_dob_date') . '/' . $Qaccount->value('customers_dob_year')); 
                ?>                                                                                                                                                                        
              </li>
              <?php
              }
            ?>
            <li><span style="float:left;"><?php echo lc_draw_label($lC_Language->get('field_customer_email_address'), 'email_address', null, true) . '</span> ' . lc_draw_input_field('email_address', $Qaccount->value('customers_email_address')); ?></li>
          </ol>
        </div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="accountEditActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span> 
        <span class="buttonRight"><a onclick="$('#account_edit').submit();" class="noDecoration"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_update'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
    </div>
  </form>
</div>
<!--content/account/account_edit.php end-->