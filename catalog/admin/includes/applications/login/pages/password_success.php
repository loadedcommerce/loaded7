<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: password_success.php v1.0 2013-08-08 datazen $
*/
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-password-success" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center mid-margin-bottom"><?php echo $lC_Language->get('text_password_success'); ?></h3>
          <p class="align-center mid-margin-bottom margin-top">
            <button type="submit" class="button glossy silver-gradient" id="submit-password"><?php echo $lC_Language->get('button_continue'); ?></button>
          </p>
        </form>
      </div>
    </div>
  </div>
  <div id="copyright-msg">
    <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_core') . ' ' . $lC_Language->get('text_version') . ' ' . utility::getVersion(); if (utility::isPro() === true) { echo '<small class="tag red-gradient mid-margin-left mid-margin-right">PRO</small>' . $lC_Language->get('text_version') . ' ' . utility::getProVersion(); } ?></p>
    <p class="anthracite" align="center"><a class="anthracite" href="http://loadedcommerce.com/support" target="_blank"><?php echo $lC_Language->get('text_get_support'); ?></a> - <a class="anthracite" href="http://loadedcommerce.com" target="_blank"><?php echo $lC_Language->get('text_get_more_loaded'); ?></a></p>
  </div>
</div>