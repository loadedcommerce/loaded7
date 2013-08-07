<?php
  /*
  $Id: password_success.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="mid-margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-password-success" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center"><?php echo $lC_Language->get('text_password_success'); ?></h3>
          <p class="small-margin-left small-margin-right margin-bottom"><?php echo $lC_Language->get('text_success_message_1'); ?></p>
          <p class="small-margin-left small-margin-right large-margin-bottom"><?php echo $lC_Language->get('text_success_message_2'); ?></p>
          <p class="align-center mid-margin-bottom">
            <button type="submit" class="button glossy full-width green-gradient" id="submit-password"><?php echo $lC_Language->get('button_continue'); ?></button>
          </p>
        </form>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>