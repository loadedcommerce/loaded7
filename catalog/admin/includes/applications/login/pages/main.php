<?php
  /*
  $Id: main.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
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
        <form method="post" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=process'); ?>" id="form-login" class="input-wrapper blue-gradient glossy" title="<?php echo $lC_Language->get('heading_title'); ?>" accept-charset="utf-8">
          <ul class="inputs black-input large">
            <!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
            <li><span class="icon-user mid-margin-right"></span><input type="text" onfocus="$('#form-wrapper').clearMessages();" name="user_name" id="user_name" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('field_username'); ?>" autocomplete="on"></li>
            <li><span class="icon-lock mid-margin-right"></span><input type="password" onfocus="$('#form-wrapper').clearMessages();" name="user_password" id="user_password" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('field_password'); ?>" autocomplete="on"></li>
          </ul>
          <p class="button-height align-center">
            <button type="submit" class="button glossy" id="login"><?php echo $lC_Language->get('button_login'); ?></button><br />
          </p>
        </form>
        <?php 
        /*
        <form method="post" action="" id="form-password" class="input-wrapper orange-gradient glossy" title="Lost password?">
          <p class="message">
            If you can’t remember your password, fill the input below with your e-mail and we’ll send you a new one:
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="mail" id="mail" value="" class="input-unstyled" placeholder="Your e-mail" autocomplete="off"></li>
          </ul>
          <button type="submit" class="button glossy full-width" id="send-password">Send new password</button>
        </form>

        <form method="post" action="" id="form-register" class="input-wrapper green-gradient glossy" title="Register">
          <p class="message">
            New user? Yay! Let us know a little bit about you before you start:
            <span class="block-arrow"><span></span></span>
          </p>
          <ul class="inputs black-input large">
            <li><span class="icon-card mid-margin-right"></span><input type="text" name="name" id="name-register" value="" class="input-unstyled" placeholder="Your name" autocomplete="off"></li>
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="mail" id="mail-register" value="" class="input-unstyled" placeholder="Your e-mail" autocomplete="off"></li>
          </ul>
          <ul class="inputs black-input large">
            <li><span class="icon-user mid-margin-right"></span><input type="text" name="login" id="login-register" value="" class="input-unstyled" placeholder="Login" autocomplete="off"></li>
            <li><span class="icon-lock mid-margin-right"></span><input type="password" name="pass" id="pass-register" value="" class="input-unstyled" placeholder="Password" autocomplete="off"></li>
          </ul>
          <button type="submit" class="button glossy full-width" id="send-register">Register</button>
        </form>
        */ ?>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br />Build 7.0.0.1a</p>
</div>