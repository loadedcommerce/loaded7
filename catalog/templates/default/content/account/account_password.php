<?php
/*
  $Id: account_password.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('account_password') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('account_password', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--ACCOUNT PASSWORD SECTION STARTS-->
  <div class="full_page">
    <!--ACCOUNT PASSWORD CONTENT STARTS-->
    <div class="content">
      <form name="account_password" id="account_password" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password=save', 'SSL'); ?>" method="post" onsubmit="return check_form(account_password);">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <!--ACCOUNT PASSWORD FIELDS STARTS-->    
        <div class="borderPadMe">
          <em style="float:right; color:#ff0000;"><?php echo $lC_Language->get('form_required_information'); ?></em>
              <ul class="form-list">
                <li>
                  <?php echo lc_draw_label($lC_Language->get('field_customer_password_current'), 'password_current', null, true); ?>
                  <div class="input-box">
                    <input type="password" name="password_current" id="password_current" class="input-text">
                  </div>
                </li>
                <li>
                  <?php echo lc_draw_label($lC_Language->get('field_customer_password_new'), 'password_new', null, true); ?>
                  <div class="input-box">
                    <input type="password" name="password_new" id="password_new" class="input-text">
                  </div>
                </li>
                <li>
                  <?php echo lc_draw_label($lC_Language->get('field_customer_password_confirmation'), 'password_confirmation', null, true); ?>
                  <div class="input-box">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-text">
                  </div>
                </li>
              </ul>
        </div>                 
        <div style="clear:both;">&nbsp;</div> 
        <!--ACCOUNT PASSWORD FIELDS ENDS-->    
        <!--ACCOUNT PASSWORD ACTIONS STARTS-->    
        <div id="accountPasswordActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span> 
          <span class="buttonRight"><a onclick="$('#account_password').submit();"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div> 
        <div style="clear:both;"></div>
        <!--ACCOUNT PASSWORD ACTIONS ENDS-->    
      </div>
      </form>
    </div>
    <!--ACCOUNT PASSWORD CONTENT ENDS-->
  </div>
<!--ACCOUNT PASSWORD SECTION ENDS-->