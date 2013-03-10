<?php
/**  
*  $Id: login.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('login') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('login', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<style>
#login_list li { margin: 10px 0; }
</style>
<!--content/account/login.php start-->
<div id="accountLogin" class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  <div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;">
    <span><?php echo $lC_Language->get('form_validation_error'); ?></span>
  </div>   
  <div class="single-bg" style="width:38%;">
    <div class="short-code-column margin-bottom">  
      <h3><?php echo $lC_Language->get('login_returning_customer_heading'); ?></h3>
      <form id="login" name="login" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login=process', 'SSL'); ?>" method="post">
      <ul id="login_list">
        <li><?php echo lc_draw_label('', 'email_address', '', false) . ' ' . lc_draw_input_field('email_address', ($_POST['email']) ? $_POST['email'] : '', 'placeholder="' . $lC_Language->get('field_customer_email_address') . '" class="txt" style="height:26px; padding-left:4px; width:99%;"'); ?></li>
        <li><?php echo lc_draw_label('', 'password', null, false) . ' ' . lc_draw_password_field('password', 'class="txt" placeholder="' . $lC_Language->get('field_customer_password') . '" style="height:26px; padding-left:4px; width:99%;"'); ?></li>
        <li><?php echo sprintf($lC_Language->get('login_returning_customer_password_forgotten'), lc_href_link(FILENAME_ACCOUNT, 'password_forgotten', 'SSL')); ?></li>
      </ul>
      <div>
        <button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_sign_in'); ?></button>
      </div>
      </form>
    <!-- /div>
    <div class="short-code-column one-half column-last" -->
      <h3 style="margin-top:40px;"><?php echo $lC_Language->get('login_new_customer_heading'); ?></h3>
      <div class="buttons-set margin-bottom">
        <a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>">
          <button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_create_account'); ?></button>
        </a>
      </div>
      <p><?php echo $lC_Language->get('login_new_customer_text'); ?></p>
    </div>
  </div>
</div>
<script>
$('#login').submit(function() {
  jQuery.validator.messages.required = "";
  var bValid = $("#login").validate({
    rules: {
      email_address: { email: true, required: true },
      password: { required: true },
    },
    invalidHandler: function(e, validator) {
      var errors = validator.numberOfInvalids();
      if (errors) {
        $("#errDiv").show().delay(5000).fadeOut('slow');
      } else {
        $("#errDiv").hide();
      }
      return false;
    }
  }).form();
  if (bValid) {     
    $('#login').submit();
  }
  return false;
});
</script>
<!--content/account/login.php end-->