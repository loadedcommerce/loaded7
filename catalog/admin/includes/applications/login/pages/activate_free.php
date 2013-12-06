<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: activate_free.php v1.0 2013-08-08 datazen $
*/ 
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-activate-free" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=free_success'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center mid-margin-bottom"><?php echo $lC_Language->get('text_activate_free_features'); ?></h3>
          <p class="align-center no-margin-top small-margin-bottom"><b><?php echo $lC_Language->get('text_free_core_activation'); ?></b></p>
          <p class="align-center no-margin-top small-margin-bottom"><?php echo $lC_Language->get('text_for'); ?></p>
          <p class="align-center"><b><?php echo str_replace('http://', '', HTTP_SERVER); ?></b></p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="activation_email" id="activation_email" value="<?php echo ((defined('STORE_OWNER_EMAIL_ADDRESS') && STORE_OWNER_EMAIL_ADDRESS != NULL) ? STORE_OWNER_EMAIL_ADDRESS : MULL); ?>" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_activation_email'); ?>" autocomplete="off"></li>
          </ul>
          <p class="align-center large-margin-bottom" style="margin-top:24px;"><span class="margin-top"><?php echo $lC_Language->get('text_adult_content'); ?>: </span><input type="checkbox" class="switch medium" data-text-on="YES" data-text-off="NO"></p>
          <p class="align-center small-margin-bottom"><button type="submit" class="button glossy silver-gradient" id="submit-password"><?php echo $lC_Language->get('button_submit_activate'); ?></button></p>
        </form>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>