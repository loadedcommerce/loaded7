<?php
/**  
*  $Id: tell_a_friend.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('tell_a_friend') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('tell_a_friend', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/products/tell_a_friend.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <form name="tell_a_friend" id="tell_a_friend" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . $lC_Product->getKeyword() . '&action=process'); ?>" method="post">
      <div class="contentBorder">
        <b><?php echo $lC_Language->get('customer_details_title'); ?></b>
        <div class="borderPadMe">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="20%"><?php echo lc_draw_label($lC_Language->get('field_tell_a_friend_customer_name'), null, 'from_name', true); ?></td>
              <td width="78%">
                <em style="float:right; color:#ff0000;"><?php echo $lC_Language->get('form_required_information'); ?></em>
                <?php echo lc_draw_input_field('from_name', ($lC_Customer->isLoggedOn() ? $lC_Customer->getName() : null)); ?>
              </td>
            </tr>
            <tr>
              <td width="20%"><?php echo lc_draw_label($lC_Language->get('field_tell_a_friend_customer_email_address'), null, 'from_email_address', true); ?></td>
              <td width="78%"><?php echo lc_draw_input_field('from_email_address', ($lC_Customer->isLoggedOn() ? $lC_Customer->getEmailAddress() : null)); ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div class="contentBorder">
        <b><?php echo $lC_Language->get('friend_details_title'); ?></b>
        <div class="borderPadMe">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="20%"><?php echo lc_draw_label($lC_Language->get('field_tell_a_friend_friends_name'), null, 'to_name', true); ?></td>
              <td width="80%">
                <em style="float:right; color:#ff0000;"><?php echo $lC_Language->get('form_required_information'); ?></em>
                <?php echo lc_draw_input_field('to_name'); ?>
              </td>
            </tr>
            <tr>
              <td width="20%"><?php echo lc_draw_label($lC_Language->get('field_tell_a_friend_friends_email_address'), null, 'to_email_address', true); ?></td>
              <td width="80%"><?php echo lc_draw_input_field('to_email_address'); ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div class="contentBorder">
        <b><?php echo $lC_Language->get('tell_a_friend_message'); ?></b>
        <div>
          <ol>
            <li><?php echo lc_draw_textarea_field('message', null, 40, 8, 'style="width: 99%;"'); ?></li>
          </ol>
        </div>
      </div>
      <div style="clear:both;"></div>
      </form>
    </div>
  </div>
  <div style="clear:both;">&nbsp;</div>
  <div id="productTellAFriendActions" class="action_buttonbar">
    <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
    <span class="buttonRight"><a onclick="$('#tell_a_friend').submit();"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
  </div>
  <div style="clear:both;"></div>
</div>
<!--content/products/tell_a_friend.php end-->