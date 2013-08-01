<?php
/**  
*  $Id: password_forgotten.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('password_forgotten') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('password_forgotten', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/account/password_forgotten.php start-->
<div class="full_page">
  <div class="short-code-column">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
    <form name="password_forgotten" id="password_forgotten" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password_forgotten=process', 'SSL'); ?>" method="post" onsubmit="return check_form(password_forgotten);">
      <div class="borderPadMe">
        <div id="passwordForgottenForm">
          <p><?php echo $lC_Language->get('password_forgotten'); ?></p><br />
          <ol>
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_email_address'), 'email_address') . ' ' . lc_draw_input_field('email_address', null, 'style="width:50%"'); ?></li>
          </ol>
        </div>
      </div> 
      <div style="clear:both;">&nbsp;</div>
      <div id="accountPasswordForgottentActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="javascript: history.go(-1)" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span> 
        <span class="buttonRight"><a onclick="$('#password_forgotten').submit();" class="noDecoration"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div> 
      <div style="clear:both;"></div>
    </form>
  </div>
</div>
<!--content/account/password_forgotten.php end-->