<?php
  /*
  $Id: activate_free.php v1.0 2013-01-01 datazen $

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
        <form method="post" action="" id="form-activate-free" class="input-wrapper blue-gradient glossy" title="Activate Free Features">
          <h3 class="align-center">Change Password</h3>
          <p class="mid-margin-bottom small-margin-left">For login: sal@loaded7.com</p>
          <ul class="inputs black-input medium">
            <i class="icon-tick icon-green align-right" style="position:absolute; top:85px; right:25px;"></i>
            <li class="with-small-padding small-margin-left small-margin-right"><span class="icon-lock small-margin-right"></span><input type="password" name="password" id="password" value="" class="input-unstyled with-small-padding" placeholder="Enter password" autocomplete="off"></li>
            <i class="icon-cross icon-red align-right" style="position:absolute; top:125px; right:25px;"></i>
            <li class="with-small-padding small-margin-left small-margin-right"><span class="icon-lock small-margin-right"></span><input type="password" name="passwordconfirm" id="passwordconfirm" value="" class="input-unstyled" placeholder="Repeat password" autocomplete="off"></li>
          </ul>
          <p class="margin-bottom small-margin-left align-center">min chars 6, mixed case, 1 number</p>
          <p class=" align-center mid-margin-bottom"><button type="submit" class="button glossy align-center" id="submit-password">Submit</button></p>
        </form>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
  $(document).ready(function() {
    var doc = $('html').addClass('js-login'),
    container = $('#container'),
    formWrapper = $('#form-wrapper'),
    formBlock = $('#form-block'),
    formViewport = $('#form-viewport'),
    forms = formViewport.children('form'),

    // Doors
    topDoor = $('<div id="top-door" class="form-door"><div></div></div>').appendTo(formViewport),
    botDoor = $('<div id="bot-door" class="form-door"><div></div></div>').appendTo(formViewport),
    doors = topDoor.add(botDoor),

    // Switch
    formSwitch = '',

    // Current form
    hash = (document.location.hash.length > 1) ? document.location.hash.substring(1) : false,

    // If layout is centered
    centered,

    // Store current form
    currentForm,

    // Animation interval
    animInt,

    // Work vars
    maxHeight = false,
    blocHeight;
  });
</script>