<?php
/*
  $Id: install_3.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/xmlhttp.js"></script>
<script language="javascript" type="text/javascript">
<!--
  var formSubmited = false;

  function handleHttpResponse() {
    if (http.readyState == 4) {
      if (http.status == 200) {
        var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(http.responseText);
        result.shift();

        if (result[0] == '1') {
          $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_database_sample_data_imported'); ?>');

          setTimeout("document.getElementById('upgradeForm3').submit();", 2000);
        } else { 
          $('#mBox').show();
          $('#mBoxContents').html('<?php echo $lC_Language->get('rpc_database_sample_data_import_error'); ?>'.replace('%s', result[1]));
        }
      }

      formSubmited = false;
    }
  }

  function prepareDB() {

      var bValid = $("#upgradeForm3").validate({
        rules: {
          CFG_STORE_OWNER_FIRST_NAME: { required: true },
          CFG_STORE_OWNER_LAST_NAME: { required: true },
          CFG_STORE_NAME: { required: true },
          CFG_STORE_OWNER_EMAIL_ADDRESS: { required: true, email: true },
          CFG_ADMINISTRATOR_USERNAME: { required: true, email: true },
          CFG_ADMINISTRATOR_PASSWORD: { required: true },
        },
        invalidHandler: function() {
          return false;
        }
      }).form();
      if (bValid) {  
      	formSubmited = true;     
      	document.getElementById('upgradeForm3').submit();
      	return true;
      }
  }
//-->
</script>
<form name="upgrade" id="upgradeForm3" action="upgrade.php?step=6" method="post" class="block wizard-enabled" onsubmit="prepareDB(); return false;">  
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="completed hide-on-mobile"><span class="wizard-step">1</span> <a style="color:#ccc;" href="index.php"><?php echo $lC_Language->get('upgrade_nav_text_1'); ?></a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">2</span> <a style="color:#ccc;" href="install.php"><?php echo $lC_Language->get('upgrade_nav_text_2'); ?></a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">3</span> <a style="color:#ccc;" href="install.php?step=2"><?php echo $lC_Language->get('upgrade_nav_text_3'); ?></a></li>
    <li class="active"><span class="wizard-step">4</span> <?php echo $lC_Language->get('upgrade_nav_text_4'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span><?php echo $lC_Language->get('upgrade_nav_text_5'); ?></li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Server</legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('upgrade_step5_page_title'); ?></h4>
      <p><?php echo $lC_Language->get('upgrade_step5_page_desc'); ?></p>
    </div>
    
    <div id="pBox" style="display:none; padding: 0px 20px 10px;">
      <p class="message blue-gradient align-center">  
        <span class="stripes animated"></span>
        <span id="pBoxContents"></span>
        <span class="block-arrow bottom"></span>
      </p>      
    </div>
    
    <div id="mBox" style="display:none; padding:0px 20px 20px 20px"> 
      <p class="message icon-warning red-gradient">   
        <span class="stripes animated"></span>
        <span id="mBoxContents"></span>
      </p> 
    </div> 
        
    <div class="field-block button-height">
      <label for="CFG_STORE_OWNER_FIRST_NAME" class="label"><b><?php echo $lC_Language->get('param_store_owner_first_name'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_owner_first_name_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_OWNER_FIRST_NAME', '', 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="CFG_STORE_OWNER_LAST_NAME" class="label"><b><?php echo $lC_Language->get('param_store_owner_last_name'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_owner_last_name_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_OWNER_LAST_NAME', '', 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="CFG_STORE_NAME" class="label"><b><?php echo $lC_Language->get('param_store_name'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_name_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_NAME', '', 'class="input" style="width:93%;"'); ?>
    </div>    
    <div class="field-block button-height">
      <label for="CFG_STORE_OWNER_EMAIL_ADDRESS" class="label"><b><?php echo $lC_Language->get('param_store_owner_email_address'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_owner_email_address_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_OWNER_EMAIL_ADDRESS', '', 'class="input" style="width:93%;"'); ?>
    </div> 
    <div class="field-block button-height">
      <label for="CFG_ADMINISTRATOR_USERNAME" class="label"><b><?php echo $lC_Language->get('param_administrator_username'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_administrator_username_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_ADMINISTRATOR_USERNAME', '', 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="CFG_ADMINISTRATOR_PASSWORD" class="label"><b><?php echo $lC_Language->get('param_administrator_password'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_administrator_password_description'); ?>"></span></label>
      <?php echo lc_draw_password_field('CFG_ADMINISTRATOR_PASSWORD', 'class="input" style="width:93%;"'); ?>
    </div>
    <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
      <a href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#upgradeForm3').submit();" class="button">
        <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
      </a>
    </div>       
  </fieldset>
  <?php
    foreach ($_POST as $key => $value) {
      if (($key != 'x') && ($key != 'y')) {
        if (is_array($value)) {
          for ($i=0, $n=sizeof($value); $i<$n; $i++) {
            echo lc_draw_hidden_field($key . '[]', $value[$i]);
          }
        } else {
          echo lc_draw_hidden_field($key, $value);
        }
      }
    }
  ?>  
</form>