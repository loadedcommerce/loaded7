<?php
/*
  $Id: install.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$db_table_types = array(array('id' => 'mysqli', 'text' => 'MySQL - MyISAM (Default)'),
                        array('id' => 'mysqli_innodb', 'text' => 'MySQL - InnoDB (Transaction-Safe)'));
?>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/xmlhttp.js"></script>
<script language="javascript" type="text/javascript">
<!--

  var dbServer;
  var dbUsername;
  var dbPassword;
  var dbName;
  var dbClass;
  var dbPrefix;

  var formSubmited = false;

  function handleHttpResponse_DoImport() {
    if (http.readyState == 4) {
      if (http.status == 200) {
        var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(http.responseText);
        result.shift();

        if (result[0] == '1') {
          $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_database_imported'); ?>');
          setTimeout("document.getElementById('installForm').submit();", 2000);
        } else {
          $('#mBoxContents').html('<?php echo $lC_Language->get('rpc_database_import_error'); ?>'.replace('%s', result[1]));
        }
      }

      formSubmited = false;
    }
  }

  function handleHttpResponse() {
    if (http.readyState == 4) {
      if (http.status == 200) {
        var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(http.responseText);
        result.shift();

        if (result[0] == '1') {
          $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_database_importing'); ?>');
          loadXMLDoc("rpc.php?action=dbImport&server=" + urlEncode(dbServer) + "&username=" + urlEncode(dbUsername) + "&password=" + urlEncode(dbPassword) + "&name=" + urlEncode(dbName) + "&class=" + urlEncode(dbClass) + "&import=0&prefix=" + urlEncode(dbPrefix), handleHttpResponse_DoImport);
        } else {
          $('#mBox').show();
          $('#mBoxContents').html('<?php echo $lC_Language->get('rpc_database_connection_error'); ?>'.replace('%s', result[1]));
          formSubmited = false;
        }
      } else {
        formSubmited = false;
      }
    }
  }

  function prepareDB() {
    if (formSubmited == true) {
      return false;
    }
    
    var bValid = $("#installForm").validate({
      rules: {
        DB_SERVER: { required: true },
        DB_SERVER_USERNAME: { required: true },
        DB_SERVER_PASSWORD: { required: false },
        DB_DATABASE: { required: true },
        DB_DATABASE_CLASS: { required: true },
        DB_TABLE_PREFIX: { required: false },
      },
      invalidHandler: function() {
      }
    }).form();
    if (bValid) {
      formSubmited = true;

      $('#pBox').show();
      $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_database_connection_test'); ?>');

      dbServer = document.getElementById("DB_SERVER").value;
      dbUsername = document.getElementById("DB_SERVER_USERNAME").value;
      dbPassword = document.getElementById("DB_SERVER_PASSWORD").value;
      dbName = document.getElementById("DB_DATABASE").value;
      dbClass = document.getElementById("DB_DATABASE_CLASS").value;
      dbPrefix = document.getElementById("DB_TABLE_PREFIX").value;

      loadXMLDoc("rpc.php?action=dbCheck&server=" + urlEncode(dbServer) + "&username=" + urlEncode(dbUsername) + "&password=" + urlEncode(dbPassword) + "&name=" + urlEncode(dbName) + "&class=" + urlEncode(dbClass), handleHttpResponse);
    }
  }

//-->
</script>
<form class="block wizard-enabled" name="install" id="installForm" action="install.php?step=2" method="post" onsubmit="prepareDB(); return false;">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="completed hide-on-mobile"><span class="wizard-step">1</span> <a style="color:#ccc;" href="index.php">Welcome</a></li>
    <li class="active"><span class="wizard-step">2</span> Database</li>
    <li class="hide-on-mobile"><span class="wizard-step">3</span> Server</li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span> Settings</li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span> Finished!</li>
  </ul>

  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Database</legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('box_info_step_1_title'); ?></h4>
      <p><?php echo $lC_Language->get('box_info_step_1_text'); ?></p>
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
          
    <div class="field-block button-height small-margin-top">
      <label for="DB_SERVER" class="label"><b><?php echo $lC_Language->get('param_database_server'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_server_description'); ?>"></span></label>
      <input type="text" name="DB_SERVER" id="DB_SERVER" value="localhost" class="input" style="width:93%;">
    </div>
    <div class="field-block button-height">
      <label for="DB_SERVER_USERNAME" class="label"><b><?php echo $lC_Language->get('param_database_username'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_username_description'); ?>"></span></label>
      <input type="text" name="DB_SERVER_USERNAME" id="DB_SERVER_USERNAME" value="" class="input" style="width:93%;">
    </div>
    <div class="field-block button-height">
      <label for="DB_SERVER_PASSWORD" class="label"><b><?php echo $lC_Language->get('param_database_password'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_password_description'); ?>"></span></label>
      <input type="password" name="DB_SERVER_PASSWORD" id="DB_SERVER_PASSWORD" value="" class="input" style="width:93%;">
    </div> 
    <div class="field-block button-height">
      <label for="DB_DATABASE" class="label"><b><?php echo $lC_Language->get('param_database_name'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_name_description'); ?>"></span></label>
      <input type="text" name="DB_DATABASE" id="DB_DATABASE" value="" class="input" style="width:93%;">
    </div>
    <div class="field-block button-height">
      <label for="DB_DATABASE_CLASS" class="label"><b><?php echo $lC_Language->get('param_database_type'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_type_description'); ?>"></span></label>
      <?php echo lc_draw_pull_down_menu('DB_DATABASE_CLASS', $db_table_types, null, 'class="input with-small-padding" style="width:96%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="DB_TABLE_PREFIX" class="label"><b><?php echo $lC_Language->get('param_database_prefix'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_database_prefix_description'); ?>"></span></label>
      <input type="text" name="DB_TABLE_PREFIX" id="DB_TABLE_PREFIX" value="lc_" class="input" style="width:93%;">
    </div>      
      <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
      <a href="index.php" class="button">
        <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
      </a>&nbsp;&nbsp;  
      <a href="javascript:void(0)" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#installForm').submit();" class="button">
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