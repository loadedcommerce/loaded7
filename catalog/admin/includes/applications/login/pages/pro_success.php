<?php
/**
  $Id: pro_success.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image no-margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-pro-success" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center mid-margin-bottom"><?php echo $lC_Language->get('heading_product_registration'); ?></h3>
          <p class="align-center small-margin-left small-margin-bottom"><b><?php echo $lC_Language->get('text_pro_registration_success'); ?></b></p>
          <p class="align-center no-margin-top small-margin-bottom"><?php echo $lC_Language->get('text_for'); ?></p>
          <p class="align-center no-margin-top small-margin-bottom"><b><?php echo str_replace('http://', '', HTTP_SERVER); ?></b></p>
          <p class="align-center margin-bottom"><span id="serial"><?php echo (isset($rInfo)) ? $rInfo->get('activation_serial') : NULL; ?></span></p>
          <p class="align-center" style="margin:22px 0 10px 0;"><button type="submit" class="button glossy silver-gradient"><?php echo $lC_Language->get('button_enter_admin'); ?></button></p>
       </form>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>