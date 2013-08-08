<?php
/**
  $Id: password_change.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
die('55');
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-password-change" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=password_success'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center"><?php echo $lC_Language->get('heading_change_password'); ?></h3>
          <p class="mid-margin-bottom small-margin-left"><?php echo $lC_Language->get('text_for_login'); ?>: <?php echo $_SESSION['user_confirmed_email']; ?></p>
          <ul class="inputs black-input medium">
            <i id="cpInput1" class="icon-cross icon-red align-right" style="position:absolute; top:85px; right:25px;"></i>
            <li class="with-small-padding small-margin-left small-margin-right">
              <span class="icon-lock small-margin-right"></span>
              <input type="password" name="password" id="password" value="" class="input-unstyled with-small-padding" placeholder="<?php echo $lC_Language->get('placeholder_enter_password'); ?>" autocomplete="off" onkeyup="validateRequirements1(this.value);">
            </li>
            <i id="cpInput2" class="icon-cross icon-red align-right" style="position:absolute; top:125px; right:25px;"></i>
            <li class="with-small-padding small-margin-left small-margin-right">
              <span class="icon-lock small-margin-right"></span>
              <input type="password" name="passwordconfirm" id="passwordconfirm" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_confirm_password'); ?>" autocomplete="off" onkeyup="comparePass(this.value);validateRequirements2(this.value);">
            </li>
          </ul>
          <input type="hidden" name="email" id="email" value="<?php echo $_SESSION['user_confirmed_email']; ?>">
          <p class="margin-bottom small-margin-left align-center"><?php echo $lC_Language->get('text_password_instructions_1') . ' ' . ACCOUNT_PASSWORD . ' ' . $lC_Language->get('text_password_instructions_2'); ?></p>
          <p class=" align-center mid-margin-bottom"><button type="submit" class="button glossy green-gradient full-width" id="submit-password" disabled><?php echo $lC_Language->get('button_submit'); ?></button></p>
        </form>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
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