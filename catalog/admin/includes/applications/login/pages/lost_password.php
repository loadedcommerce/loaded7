<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lost_password.php v1.0 2013-08-08 datazen $
*/
?>
<div id="container" style="position:absolute; top:35%;">
  <hgroup id="login-title" class="mid-margin-bottom">
    <h1 class="login-title-image margin-bottom"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <p id="error_message" class="message red-gradient align-center mid-margin-bottom" style="display: none;"><span id="error_text"></span><span class="block-arrow bottom"><span></span></span></p>                                                                                                                                                                                         
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <?php 
        if (isset($_SESSION['user_not_exists']) && $_SESSION['user_not_exists'] === true) { 
          ?>
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
          <?php 
        } else { 
          ?>
          <form id="form-lost-password-key" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=password_change'); ?>" class="input-wrapper blue-gradient glossy" method="post">
            <h3 class="align-center mid-margin-bottom"><?php echo $lC_Language->get('heading_lost_password'); ?></h3>
            <ul class="inputs black-input large">
              <li>
                <span class="icon-key mid-margin-right"></span>
                <input type="text" name="key" id="key" value="<?php echo (isset($_GET['key']) && $_GET['key'] != '') ? $_GET['key'] : ''; ?>" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_manual_key_entry'); ?>" autocomplete="off">
                <input type="hidden" name="email" id="email" value="<?php echo (isset($_SESSION['user_confirmed_email'])) ? $_SESSION['user_confirmed_email'] : ((isset($rInfo)) ? $rInfo->get('password_email') : NULL); ?>">
              </li>
            </ul>
            <p class="small-margin-left no-margin-top mid-margin-bottom"><?php echo $lC_Language->get('text_lost_password_key_instructions_1'); ?></p>
            <p class="align-center strong no-margin-top no-margin-bottom"><?php echo $_SESSION['user_confirmed_email']; ?></p>
            <p class="small-margin-left mid-margin-top"><?php echo $lC_Language->get('text_lost_password_key_instructions_2'); ?></p>
            <p class=" align-center large-margin-bottom" style="margin-top:20px;">
              <button type="button" class="button glossy silver-gradient float-left large-margin-left" onclick="javascript:location.href='<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>';"><?php echo $lC_Language->get('button_cancel'); ?></button>
              <button type="submit" class="button glossy green-gradient float-right large-margin-right"><?php echo $lC_Language->get('button_submit'); ?></button>
            </p>
            <p>&nbsp;</p>
          </form>
          <?php 
        } 
        ?>
      </div>
    </div>
  </div>
  <p class="anthracite mid-margin-top" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
$('#form-lost-password-key').submit(function(event) {  
  $("#form-lost-password-key").bind("submit", preventDefault(event));
  var email = '<?php echo (isset($_SESSION['user_confirmed_email'])) ? urlencode($_SESSION['user_confirmed_email']) : ((isset($rInfo)) ? urlencode($rInfo->get('password_email')) : NULL); ?>';
  var key = $('#key').val();
  if (key == '') {
    $('#error_message').slideDown('fast').delay(3000).slideUp('fast');
    $('#error_text').html('<?php echo $lC_Language->get('ms_error_blank_key'); ?>');   
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=lostPasswordConfirmKey&key=KEY&email=EMAIL'); ?>'; 
  $.getJSON(jsonLink.replace('KEY', key).replace('EMAIL', email),        
    function (data) { 
      if (data.rpcStatus != 1) {
        $('#error_message').slideDown('fast').delay(3000).slideUp('fast');
        $('#error_text').html('<?php echo $lC_Language->get('ms_error_key_invalid'); ?>');   
        return false;
      }
      $("#form-lost-password-key").unbind("submit", preventDefault(event)).submit();
      return true;      
    }              
  );
});

/**
* Function to prevent default action
* @param object the event 
*/      
function preventDefault(e) {
  e.preventDefault();
}

</script>