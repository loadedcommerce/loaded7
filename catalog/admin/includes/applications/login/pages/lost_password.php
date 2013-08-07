<?php
  /*
  $Id: lost_password.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
//$_SESSION['user_not_exists'] = null;
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="mid-margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <?php if (isset($_SESSION['user_not_exists']) && $_SESSION['user_not_exists'] === true) { ?>
        <form id="form-no-user" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=lost_password'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('title_lost_password'); ?>?">
          <p class="small-margin-left small-margin-right">
            <?php echo $lC_Language->get('text_lost_password_no_user'); ?>
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="password_email" id="password_email" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_password_email'); ?>" autocomplete="off"></li>
          </ul>
          <p class="full-width"><button type="submit" class="button glossy green-gradient full-width" id="no-user"><?php echo $lC_Language->get('button_submit'); ?></button></p>
        </form>
        <?php } else { ?>
        <form id="form-lost-password" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=password_change'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center"><?php echo $lC_Language->get('heading_lost_password'); ?></h3>
          <ul class="inputs black-input large">
            <li>
              <span class="icon-key mid-margin-right"></span>
              <input type="text" name="key" id="key" value="<?php echo (isset($_GET['key']) && $_GET['key'] != '') ? $_GET['key'] : ''; ?>" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_manual_key_entry'); ?>" autocomplete="off">
              <input type="hidden" name="email" id="email" value="<?php echo (isset($_SESSION['user_confirmed_email'])) ? $_SESSION['user_confirmed_email'] : $_GET['email']; ?>">
            </li>
          </ul>
          <p class="small-margin-left no-margin-top">
            <?php echo $lC_Language->get('text_lost_password_key_instructions_1'); ?>
            <b><?php echo $_SESSION['user_confirmed_email']; ?></b>
          </p>
          <p class="small-margin-left"><?php echo $lC_Language->get('text_lost_password_key_instructions_2'); ?></p>
          <p class=" align-center large-margin-bottom margin-top">
            <button type="button" class="button glossy grey-gradient float-left" onclick="javascript:location.href='<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>';"><?php echo $lC_Language->get('button_cancel'); ?></button>
            <button type="submit" class="button glossy green-gradient float-right"><?php echo $lC_Language->get('button_submit'); ?></button>
          </p>
          <p>&nbsp;</p>
        </form>
        <?php } ?>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
$(document).ready(function() {
  $('body').removeClass('clearfix with-menu with-shortcuts');
  $('html').addClass('linen');
});
</script>