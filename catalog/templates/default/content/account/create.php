<?php
/*
  $Id: create.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('create') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('create', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--CREATE ACCOUNT SECTION STARTS-->
  <div id="createAccount" class="full_page">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
    <form name="create" id="create" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create=save', 'SSL'); ?>" method="post" onsubmit="return check_form(create);">
    <div class="content"> 
      <div class="short-code-column">
        <div class="createAccountFormLeft">
          <ol>
            <?php
              if (ACCOUNT_GENDER > -1) {
                $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
                                      array('id' => 'f', 'text' => $lC_Language->get('gender_female')));
            ?>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_gender'), 'fake', null, (ACCOUNT_GENDER > 0)) . ' ' . lc_draw_radio_field('gender', $gender_array); ?></li>
            <?php
              }
            ?>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_first_name'), 'firstname', null, true) . ' ' . lc_draw_input_field('firstname'); ?></li>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_last_name'), 'lastname', null, true) . ' ' . lc_draw_input_field('lastname'); ?></li>
            <?php
              if (ACCOUNT_DATE_OF_BIRTH == '1') {
            ?>
            <li style="height:32px;"><span style="float:left;">
            <?php 
              echo lc_draw_label($lC_Language->get('field_customer_date_of_birth'), 'dob_days', null, true) . '</span>&nbsp;' . 
                   lc_draw_input_field('dob'); 
            ?>
            </li>
            <?php
              }
            ?>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_email_address'), 'email_address', '', true) . ' ' . lc_draw_input_field('email_address', ($_POST['email']) ? $_POST['email'] : ''); ?></li>
            <?php
              if (ACCOUNT_NEWSLETTER == '1') {
            ?>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_newsletter'), 'newsletter') . ' ' . lc_draw_checkbox_field('newsletter', '1', '', ($_POST['email']) ? 'checked="checked"' : ''); ?></li>
            <?php
              }
            ?>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_password'), 'password', null, true) . ' ' . lc_draw_password_field('password', 'onFocus="if (this.value != \'\') { this.value = \'\'; }"'); ?></li>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_password_confirmation'), 'confirmation', null, true) . ' ' . lc_draw_password_field('confirmation'); ?></li>
          </ol>
          <?php
            if (DISPLAY_PRIVACY_CONDITIONS == '1') {
          ?>
          <div>
            <?php echo sprintf($lC_Language->get('create_account_terms_description'), lc_href_link(FILENAME_INFO, 'privacy', 'AUTO')) . '<br /><br /><ol style="list-style:none;"><li>' . lc_draw_checkbox_field('privacy_conditions', array(array('id' => 1, 'text' => $lC_Language->get('create_account_terms_confirm')))) . '</li></ol>'; ?>
          </div>
          <?php
            }
          ?>
          <div style="clear:both;">&nbsp;</div>
          <div class="action_buttonbar">
            <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
            <span class="buttonRight"><a onclick="$('#create').submit();"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
          </div>
        </div>
      </div>
      <div style="clear:both;"></div> 
    </div>
    </form>
  </div>
<!--CREATE ACCOUNT SECTION ENDS-->