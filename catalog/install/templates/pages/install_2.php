<?php
/*
  $Id: install_2.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$www_location = 'http://' . $_SERVER['HTTP_HOST'];
$www_ssl_location = 'https://' . $_SERVER['HTTP_HOST'];
if (isset($_SERVER['REQUEST_URI']) && (empty($_SERVER['REQUEST_URI']) === false)) {
  $www_location .= $_SERVER['REQUEST_URI'];
  $www_ssl_location .= $_SERVER['REQUEST_URI'];
} else {
  $www_location .= $_SERVER['SCRIPT_FILENAME'];
  $www_ssl_location .= $_SERVER['SCRIPT_FILENAME'];
}
$www_location = substr($www_location, 0, strpos($www_location, 'install'));
$www_ssl_location = substr($www_ssl_location, 0, strpos($www_ssl_location, 'install'));
$dir_fs_www_root = lc_realpath(dirname(__FILE__) . '/../../../') . '/';
?>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/xmlhttp.js"></script>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/autocomplete.js"></script>
<script language="javascript" type="text/javascript">
<!--
String.prototype.wordWrap = function(m, b, c){
  var i, j, s, r = this.split("\n");
  if(m > 0) for(i in r){
      for(s = r[i], r[i] = ""; s.length > m;
          j = c ? m : (j = s.substr(0, m).match(/\S*$/)).input.length - j[0].length
          || m,
          r[i] += s.substr(0, j) + ((s = s.substr(j)).length ? b : "")
      );
      r[i] += s;
  }
  return r.join("\n");
};

var cfgWork;
var formSubmited = false;

function handleHttpResponse() {
  if (http.readyState == 4) {
    if (http.status == 200) {
      var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(http.responseText);
      result.shift();

      if (result[0] == '1') {
        document.getElementById('pBoxContents').innerHTML = '<?php echo $lC_Language->get('rpc_work_directory_configured'); ?>';

        setTimeout("document.getElementById('installForm2').submit();", 2000);
      } else if (result[0] == '0') {
        $("div#mBox").attr("tabindex",-1).focus();
        $('#mBox').show();
        var dir = ('<?php echo $lC_Language->get('rpc_work_directory_error_not_writeable'); ?>').replace('%s', result[1].wordWrap(30, '<br />', true));
        $('#mBoxContents').html(dir);
      } else {
        $("div#mBox").attr("tabindex",-1).focus();
        $('#mBox').show();
        var dir = ('<?php echo $lC_Language->get('rpc_work_directory_error_non_existent'); ?>').replace('%s', result[1].wordWrap(30, '<br />', true));
        $('#mBoxContents').html(dir);
      }
    }

    formSubmited = false;
  }
}

function prepareWork() {
  if (formSubmited == true) {
    return false;
  }
  
  var bValid = $("#installForm2").validate({
    rules: {
      HTTP_WWW_ADDRESS: { required: true },
      DIR_FS_DOCUMENT_ROOT: { required: true },
    },
    invalidHandler: function() {
      return false;
    }
  }).form();
  if (bValid) {  
    if (returnUsed == true) {
      returnUsed = false;

      return false;
    }

    formSubmited = true;

    $("div#pBox").attr("tabindex",-1).focus();
    $('#pBox').show();
    $('#pBoxContents').html('<?php echo $lC_Language->get('rpc_work_directory_test'); ?>');

    cfgWork = $("#HTTP_WORK_DIRECTORY").val();

    loadXMLDoc("rpc.php?action=checkWorkDir&dir=" + urlEncode(cfgWork), handleHttpResponse);
  }
}
//-->
</script>
<form name="install" id="installForm2" action="install.php?step=3" method="post" class="block wizard-enabled" onsubmit="prepareWork(); return false;">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="completed hide-on-mobile"><span class="wizard-step">1</span> <a style="color:#ccc;" href="index.php">Welcome</a></li>
    <li class="completed hide-on-mobile"><span class="wizard-step">2</span> <a style="color:#ccc;" href="install.php">Database</a></li>
    <li class="active"><span class="wizard-step">3</span> Server</li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span> Settings</li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span> Finished!</li>
  </ul>
  <fieldset class="wizard-fieldset fields-list current active">
    <legend class="legend">Server</legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('box_info_step_2_title'); ?></h4>
      <p><?php echo $lC_Language->get('box_info_step_2_text'); ?></p>
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
      <label for="HTTP_WWW_ADDRESS" class="label"><b><?php echo $lC_Language->get('param_web_address'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_web_address_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('HTTP_WWW_ADDRESS', $www_location, 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="web_use_ssl" class="label"><b><?php echo $lC_Language->get('param_web_use_ssl'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_web_use_ssl_description'); ?>"></span></label>
      <?php echo lc_draw_checkbox_field('web_use_ssl', 'true', true, 'onclick="$(\'#sslInputContainer\').toggle();"'); ?>
    </div>    
    <div id="sslInputContainer" class="field-block button-height">
      <label for="HTTPS_WEB_ADDRESS" class="label"><b><?php echo $lC_Language->get('param_web_ssl_address'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_web_ssl_address_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('HTTPS_WEB_ADDRESS', $www_ssl_location, 'class="input" style="width:93%;"'); ?>
    </div>      
    <div class="field-block button-height">
      <label for="DIR_FS_DOCUMENT_ROOT" class="label"><b><?php echo $lC_Language->get('param_web_root_directory'); ?></b>&nbsp;<span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_web_root_directory_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('DIR_FS_DOCUMENT_ROOT', $dir_fs_www_root, 'class="input" style="width:93%;"'); ?>
    </div>
    <div class="field-block button-height">
      <label for="HTTP_WORK_DIRECTORY" class="label"><b><?php echo $lC_Language->get('param_web_work_directory'); ?></b><span style="cursor:pointer;" class="hide-on-mobile hide-on-tablet icon-info-round icon-blue with-tooltip with-small-padding" data-tooltip-options='{"classes":["blue-gradient"],"position":"right"}' title="<?php echo $lC_Language->get('param_web_work_directory_description'); ?>"></span></label>
      <?php echo lc_draw_input_field('HTTP_WORK_DIRECTORY', $dir_fs_www_root . 'includes/work', 'class="input" style="width:93%;"'); ?>
    </div> 
    <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
      <a href="index.php" class="button">
        <span class="button-icon red-gradient glossy"><span class="icon-cross"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_cancel')); ?>
      </a>&nbsp;&nbsp;  
      <a href="javascript(void);" onclick="$('#mBox').hide(); $('#pBox').hide(); $('#installForm2').submit();" class="button">
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
<script>
<!--
  new autoComplete(document.getElementById('HTTP_WORK_DIRECTORY'), 'divAutoComplete');
//-->
</script>