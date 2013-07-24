<?php
  /*
  $Id: main.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<div id="container">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
       
        <form id="form-login" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=process'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('heading_title'); ?>" accept-charset="utf-8">
          <ul class="inputs black-input large">
            <!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
            <li><span class="icon-user mid-margin-right"></span><input type="text" onfocus="$('#form-wrapper').clearMessages();" name="user_name" id="user_name" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_username'); ?>" autocomplete="on"></li>
            <li><span class="icon-lock mid-margin-right"></span><input type="password" onfocus="$('#form-wrapper').clearMessages();" name="user_password" id="user_password" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_password'); ?>" autocomplete="on"></li>
          </ul>
          <p class="button-height align-center">
            <button type="submit" class="button glossy" id="login"><?php echo $lC_Language->get('button_login'); ?></button><br />
          </p>
        </form> 
        
        <form id="form-password" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=forgot_password'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('title_lost_password'); ?>?">
          <p class="message">
            <?php echo $lC_Language->get('text_send_new_password_instructions'); ?>
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="password_email" id="password_email" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_password_email'); ?>" autocomplete="off"></li>
          </ul>
          <button type="submit" class="button glossy green-gradient full-width" id="lost-password"><?php echo $lC_Language->get('button_lost_password'); ?></button>
        </form>
        
        <form id="form-activate" method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=activate_pro'); ?>" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('title_register'); ?>">
          <h3 class="align-center">Product Registration</h3>
          <button type="button" class="button glossy green-gradient full-width" id="activate-free" onclick="javascript:alert('You should try Pro you WUSS!');"><?php echo $lC_Language->get('button_activate_free'); ?></button>
          <p class="align-center mid-margin-top"><?php echo $lC_Language->get('text_or'); ?></p>
          <ul class="inputs black-input large">
            <li><span class="icon-unlock mid-margin-right"></span><input type="text" name="serial" id="serial" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_pro_serial'); ?>" autocomplete="off"></li>
          </ul>
          <button type="submit" class="button glossy red-gradient full-width" id="activate-pro"><?php echo $lC_Language->get('button_activate_pro'); ?></button>
        </form>
        
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>