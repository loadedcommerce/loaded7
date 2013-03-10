<?php
/**  
*  $Id: account_password.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('account_password') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('account_password', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/account/account_password.php start-->
<div class="full_page">
  <div class="content">
    <form name="account_password" id="account_password" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password=save', 'SSL'); ?>" method="post">
        <div class="short-code-column">
          <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
          <div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;"> <span><?php echo $lC_Language->get('form_validation_error'); ?></span> </div>
          <div class="single-bg">
            <div class="embed-form short-code-column one-half no-margin-bottom">
              <ul id="personal_details">
                <li><?php echo lc_draw_label('', 'password_current', null, false) . ' ' . lc_draw_password_field('password_current', 'placeholder="' . $lC_Language->get('field_customer_password_current') . '" class="txt" style="width:99%;"'); ?></li>
                <li><?php echo lc_draw_label('', 'password_new', null, false) . ' ' . lc_draw_password_field('password_new', 'placeholder="' . $lC_Language->get('field_customer_password_new') . '" class="txt" style="width:99%;"'); ?></li>
                <li><?php echo lc_draw_label('', 'password_confirmation', null, false) . ' ' . lc_draw_password_field('password_confirmation', 'placeholder="' . $lC_Language->get('field_customer_password_confirmation') . '" class="txt" style="width:99%;"'); ?></li>
              </ul>
            </div>
          </div>
        </div>               
        <div style="clear:both;">&nbsp;</div> 
        <div id="accountPasswordActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span> 
          <span class="buttonRight"><a onclick="$('#account_password').submit();"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div> 
        <div style="clear:both;"></div>
      </div>
    </form>
  </div>
</div>
<!--content/account/account_password.php end-->