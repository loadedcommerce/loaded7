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

  var dbServer = "<?php echo $_POST['DB_SERVER']; ?>";
  var dbUsername = "<?php echo $_POST['DB_SERVER_USERNAME']; ?>";
  var dbPassword = "<?php echo $_POST['DB_SERVER_PASSWORD']; ?>";
  var dbName = "<?php echo $_POST['DB_DATABASE']; ?>";
  var dbClass = "<?php echo $_POST['DB_DATABASE_CLASS']; ?>";
  var dbPrefix = "<?php echo $_POST['DB_TABLE_PREFIX']; ?>";

  var formSubmited = false;

  function handleHttpResponse() {
    if (http.readyState == 4) {
      if (http.status == 200) {
        var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(http.responseText);
        result.shift();

        if (result[0] == '1') {
          $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_database_sample_data_imported'); ?>');

          setTimeout("document.getElementById('installForm3').submit();", 2000);
        } else { 
          $('#mBox').show();
          $('#mBoxContents').html('<?php echo $lC_Language->get('rpc_database_sample_data_import_error'); ?>'.replace('%s', result[1]));
        }
      }

      formSubmited = false;
    }
  }

  function prepareDB() {
    if (document.getElementById("DB_INSERT_SAMPLE_DATA").checked) {
      if (formSubmited == true) {
        return false;
      }

      var bValid = $("#installForm3").validate({
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

          $('#pBox').show();
          $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_database_sample_data_importing'); ?>');

          loadXMLDoc("rpc.php?action=dbImportSample&server=" + urlEncode(dbServer) + "&username=" + urlEncode(dbUsername) + "&password=" + urlEncode(dbPassword) + "&name=" + urlEncode(dbName) + "&class=" + urlEncode(dbClass) + "&prefix=" + urlEncode(dbPrefix), handleHttpResponse);
        }
      } else {
      document.getElementById('installForm3').submit();
    }
  }
//-->
</script>
<form name="install" id="installForm3" action="install.php?step=4" method="post" class="block wizard-enabled" onsubmit="prepareDB(); return false;">  
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="completed hide-on-mobile"><span class="wizard-step">1</span> <a style="color:#ccc;" href="index.php">Welcome</a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">2</span> <a style="color:#ccc;" href="install.php">Database</a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">3</span> <a style="color:#ccc;" href="install.php?step=3">Server</a></li>
    <li class="active"><span class="wizard-step">4</span> Settings</li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span> Finished!</li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Server</legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('box_info_step_3_title'); ?></h4>
      <p><?php echo $lC_Language->get('box_info_step_3_text'); ?></p>
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
      <?php echo lc_draw_input_field('CFG_STORE_OWNER_FIRST_NAME', null, 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="CFG_STORE_OWNER_LAST_NAME" class="label"><b><?php echo $lC_Language->get('param_store_owner_last_name'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_owner_last_name_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_OWNER_LAST_NAME', null, 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="CFG_STORE_NAME" class="label"><b><?php echo $lC_Language->get('param_store_name'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_name_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_NAME', null, 'class="input" style="width:93%;"'); ?>
    </div>    
    <div class="field-block button-height">
      <label for="CFG_STORE_OWNER_EMAIL_ADDRESS" class="label"><b><?php echo $lC_Language->get('param_store_owner_email_address'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_store_owner_email_address_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_STORE_OWNER_EMAIL_ADDRESS', null, 'class="input" style="width:93%;"'); ?>
    </div> 
    <div class="field-block button-height">
      <label for="CFG_ADMINISTRATOR_USERNAME" class="label"><b><?php echo $lC_Language->get('param_administrator_username'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_administrator_username_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('CFG_ADMINISTRATOR_USERNAME', null, 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="CFG_ADMINISTRATOR_PASSWORD" class="label"><b><?php echo $lC_Language->get('param_administrator_password'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_administrator_password_description'); ?>"></span></label>
      <?php echo lc_draw_password_field('CFG_ADMINISTRATOR_PASSWORD', 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="DB_INSERT_SAMPLE_DATA" class="label"><b><?php echo $lC_Language->get('param_database_import_sample_data'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_import_sample_data_description'); ?>"></span></label>
      <?php echo lc_draw_checkbox_field('DB_INSERT_SAMPLE_DATA', 'true', true) . '&nbsp;' . $lC_Language->get('param_database_import_sample_data'); ?>
    </div>    
    <div id="buttonContainer" class="margin-right" style="float:right">
      <a href="index.php" class="button">
        <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
      </a>&nbsp;&nbsp;  
      <a href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#installForm3').submit();" class="button">
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