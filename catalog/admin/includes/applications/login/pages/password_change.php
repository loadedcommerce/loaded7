<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: password_change.php v1.0 2013-08-08 datazen $
*/
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-password-change" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=password_success'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center mid-margin-bottom"><?php echo $lC_Language->get('heading_change_password'); ?></h3>
          <p class="align-center mid-margin-bottom"><?php echo $lC_Language->get('text_for'); ?> <b><?php echo (isset($_SESSION['user_confirmed_email'])) ? $_SESSION['user_confirmed_email'] : ((isset($rInfo)) ? $rInfo->get('password_email') : NULL); ?></b></p>
          <ul class="inputs black-input medium no-margin-bottom">
            <span id="cpInput1" class="icon-cross icon-red align-right" style="position:absolute; top:73px; right:25px;"></span>
            <li class="with-small-padding small-margin-left small-margin-right">
              <span class="icon-lock small-margin-right"></span>
              <input type="password" name="password" id="password" value="" class="input-unstyled with-small-padding" placeholder="<?php echo $lC_Language->get('placeholder_enter_password'); ?>" autocomplete="off" onkeyup="validateRequirements1(this.value);">
            </li>
            <span id="cpInput2" class="icon-cross icon-red align-right" style="position:absolute; top:113px; right:25px;"></span>
            <li class="with-small-padding small-margin-left small-margin-right">
              <span class="icon-lock small-margin-right"></span>
              <input type="password" name="passwordconfirm" id="passwordconfirm" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_confirm_password'); ?>" autocomplete="off" onkeyup="comparePass(this.value);validateRequirements2(this.value);">
            </li>
          </ul>
          <input type="hidden" name="email" id="email" value="<?php echo (isset($_SESSION['user_confirmed_email'])) ? $_SESSION['user_confirmed_email'] : ((isset($rInfo)) ? $rInfo->get('password_email') : NULL); ?>">
          <p class="align-center no-margin-bottom small-margin-top"><small class="align-center white"><i><?php echo sprintf($lC_Language->get('text_password_instructions'), ACCOUNT_PASSWORD); ?></i></small></p>
          <p class="align-center mid-margin-bottom margin-top"><button type="submit" class="button glossy silver-gradient" id="submit-password" disabled><?php echo $lC_Language->get('button_update_password'); ?></button></p>
        </form>
      </div>
    </div>
  </div>
  <p class="anthracite mid-margin-top" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
function comparePass(val) {
  var pass = $("#password").val();
  if (val == pass) {
    $("#submit-password").removeAttr("disabled");
  } else {
    $("#submit-password").attr("disabled", "disabled");
  }
}

function validateRequirements1(val) {
  var containsDigits = /[0-9]/.test(val);
  var containsUpper = /[A-Z]/.test(val);
  var containsLower = /[a-z]/.test(val);
  if (val.length >= '<?php echo ACCOUNT_PASSWORD; ?>' && containsDigits && containsUpper && containsLower) {
    $("#cpInput1").removeClass("icon-cross icon-red").addClass("icon-tick icon-green");
  }
}

function validateRequirements2(val) {
  var containsDigits = /[0-9]/.test(val);
  var containsUpper = /[A-Z]/.test(val);
  var containsLower = /[a-z]/.test(val);
  if (val.length >= '<?php echo ACCOUNT_PASSWORD; ?>' && containsDigits && containsUpper && containsLower) {
    $("#cpInput2").removeClass("icon-cross icon-red").addClass("icon-tick icon-green");
  }
}
</script>