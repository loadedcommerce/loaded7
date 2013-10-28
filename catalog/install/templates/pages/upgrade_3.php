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
switch($_POST['upgradeMethod']){
	case 'R':
	{
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
	}
	break;

	case 'D':
	{
	}
	break;
	
	default:
	{
		echo '<pre>';
//		print_r($_POST);
		echo '</pre>';
	}
	break;
}
?>
<script type="text/css">
	.importstatus { 
		height: 20px;
	}
	.step_err_msg{
		margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;
	}
</script>
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
<form name="upgradeForm" id="upgradeForm" action="upgrade.php?step=4" method="post" class="block wizard-enabled">
  <span style="width:48%;" class="with-small-padding" style="padding: 10px 0 10px 0;" id="image"><img src="templates/img/logo.png" border="0"></span>
  <span class="with-small-padding float-right hide-on-mobile" id="logoContainer"><img style="padding-right:10px;" src="templates/img/new_version.png" border="0"></span>
  <ul class="wizard-steps">
    <li class="hide-on-mobile"><span class="wizard-step">1</span>  <a style="color:#ccc;" href="index.php"><?php echo $lC_Language->get('upgrade_nav_text_1'); ?></a></li>
    <li class="hide-on-mobile"><span class="wizard-step">2</span> <a style="color:#ccc;" href="install.php"><?php echo $lC_Language->get('upgrade_nav_text_2'); ?></a></li>
    <li class="active"><span class="wizard-step">3</span><?php echo $lC_Language->get('upgrade_nav_text_3'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">4</span><?php echo $lC_Language->get('upgrade_nav_text_4'); ?></li>
    <li class="hide-on-mobile"><span class="wizard-step">5</span><?php echo $lC_Language->get('upgrade_nav_text_5'); ?></li>
  </ul>

  <fieldset class="wizard-fieldset fields-list current active">
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('upgrade_step3_page_title'); ?></h4>
      <p><?php echo $lC_Language->get('upgrade_step3_page_desc'); ?></p>
    </div>
    
    <div id="pBox" style="display:none; padding: 0px 20px 10px;">
      <p class="message blue-gradient align-center">  
        <span class="stripes animated"></span>
        <span id="pBoxContents"></span>
        <span class="block-arrow bottom"></span>
      </p>      
    </div>
    
    <div id="mBox" style="display:none; padding:0px 20px 10px"> 
      <p class="message icon-warning red-gradient">   
        <span id="mBoxContents"></span>
      </p> 
    </div>      
    
    <div id="mBoxSuccess" style="display:none; padding:0px 20px 10px"> 
      <p class="message green-gradient">   
        <span id="mBoxSuccessContents"></span>
      </p> 
    </div>      
    
    <div class="with-padding">  
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CATEGORIES" class="label"><b><?php echo $lC_Language->get('param_import_categories'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_categories" style="height:25px; padding-top:5px;">   
    		  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    		  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    		  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_categories" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_categories"></span>
    	    </p> 
    	  </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_PRODUCTS" class="label"><b><?php echo $lC_Language->get('param_import_products'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_products" style="height:25px; padding-top:5px;">   
    		  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    		  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    		  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_products" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_products"></span>
    	    </p> 
    	  </div>      
      </div>      
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ATTRIBUTES" class="label"><b><?php echo $lC_Language->get('param_import_attributes'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_attributes" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_attributes" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_attributes"></span>
    	    </p> 
    	  </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CUSTOMERS" class="label"><b><?php echo $lC_Language->get('param_import_customers'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_customers" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_customers" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_customers"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CUSTOMER_GROUPS" class="label"><b><?php echo $lC_Language->get('param_import_customer_groups'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_customer_groups" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_customer_groups" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_customer_groups"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS" class="label"><b><?php echo $lC_Language->get('param_import_orders'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_orders" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_orders" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_orders"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CDS" class="label"><b><?php echo $lC_Language->get('param_import_cds'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_cds" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_cds" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_cds"></span>
    	    </p> 
    	  </div>      
      </div> 

      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ADMIN" class="label"><b><?php echo $lC_Language->get('param_import_admin'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_administrators" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_administrators" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_administrators"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_NEWSLETTER" class="label"><b><?php echo $lC_Language->get('param_import_newsletter'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_newsletter" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  <div id="eBox_newsletter" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_newsletter"></span>
    	    </p> 
    	  </div>      
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_BANNERS" class="label"><b><?php echo $lC_Language->get('param_import_banners'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_banners" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_banners" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_banners"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CONFIG" class="label"><b><?php echo $lC_Language->get('param_import_config'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_configuration" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_configuration" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_configuration"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_COUPONS" class="label"><b><?php echo $lC_Language->get('param_import_coupon'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_coupons" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_coupons" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_coupons"></span>
    	    </p> 
    	  </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_TAX" class="label"><b><?php echo $lC_Language->get('param_import_tax'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_taxclasses" style="height:25px; padding-top:5px;">   
    			  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    			  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_taxclasses" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_taxclasses"></span>
    	    </p> 
    	  </div>      
      </div> 
    </div>
    <div id="buttonContainer" class="large-margin-top margin-right" style="float:right; display:none;">
      <a id="btn_continue" href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide();$('#upgradeForm').submit();" class="button">
        <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_continue')); ?>
      </a>
      <a id="btn_retry" href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide();$('#upgradeForm').submit();" class="button">
        <span class="button-icon blue-gradient glossy"><span class="icon-right-round"></span></span>
        <?php echo addslashes($lC_Language->get('image_button_retry')); ?>
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

	$(document).ready(function() {

		$('.tick').hide();
		$('.progress').hide();
		$('.cross').hide();

    var 
        processes = [
            {
                type: '_categories'
            },
            {
                type: '_products'
            },
            {
                type: '_attributes'
            },
            {
                type: '_customers'
            },
            {
                type: '_customer_groups'
            },
            {
                type: '_orders'
            },
            {
                type: '_cds'
            },
            {
                type: '_administrators'
            },
            {
                type: '_newsletter'
            },
            {
                type: '_banners'
            },
            {
                type: '_configuration'
            },
            {
                type: '_coupons'
            },
            {
                type: '_taxclasses'
            }
        ];
        
		$('#pBoxContents').html('UPGRADE IN PROGRESS');
    $('#pBox').show();
    var _rslt = ""; 
    var _err = false;
    var _errmsg = "";
    $.each(processes, function(i, data) {
    	_rslt = "";
			$('body').queue(function() {
        _rslt = doImport(data['type']);
        if(_rslt === ""){  }
        else { _err = true ; _errmsg = _errmsg + '<p>' + _rslt; 
					$('#eBoxContents'+data['type']).html(_rslt);
    			$('#eBox'+data['type']).show();
        
        }
      });    	
    });    
    $('#pBox').hide();
    
    if(_err === false){
			$('#mBoxSuccessContents').html('UPGRADE COMPLETE');
    	$('#mBoxSuccess').show();

			$("#upgradeForm").attr("action", "upgrade.php?step=4");
			$('#btn_continue').show();
			$('#btn_retry').hide();
    }
    else{
			$('#mBoxContents').html('<?php echo $lC_Language->get('upgrade_step3_page_errfound'); ?>');
    	$('#mBox').show();

			$("#upgradeForm").attr("action", "upgrade.php?step=3");
			$('#btn_continue').hide();
			$('#btn_retry').show();
    }
		
		$('#buttonContainer').show();
	});

	var doImport = function(datatype){
		var _success = false;
		var _emsg = "";
	  $.ajax({
 		 					url: "rpc.php?action=import"+datatype,
 		 					type: 'POST',
 		 					data : $("form").serialize(),
 		 					async : false, 
  						cache: false,
  						beforeSend : function() { 
  							$("#pBoxContainer"+datatype+" #img_copy_progress").show();
  							$("#pBoxContainer"+datatype+" #img_copy_cross").hide();
  							$("#pBoxContainer"+datatype+" #img_copy_tick").hide();
						 	},
    					success : function(data) {                          
  							$("#pBoxContainer"+datatype+" #img_copy_progress").hide();
  							$("#pBoxContainer"+datatype+" #img_copy_cross").hide();
  							$("#pBoxContainer"+datatype+" #img_copy_tick").show();
  							
	    		    		var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(data);
	    		    		result.shift();
									
	    		    		if (result[0] == '1') {
  									$("#pBoxContainer"+datatype+" #img_copy_progress").hide();
  									$("#pBoxContainer"+datatype+" #img_copy_cross").hide();
  									$("#pBoxContainer"+datatype+" #img_copy_tick").show();
									} else {
  									$("#pBoxContainer"+datatype+" #img_copy_progress").hide();
  									$("#pBoxContainer"+datatype+" #img_copy_cross").show();
  									$("#pBoxContainer"+datatype+" #img_copy_tick").hide();
  									
										_success = false;
										_emsg = result[1];
  									$('body').clearQueue();
	    		    		}
  							
    					  // Que up next ajax call
    					  $('body').dequeue();
    					},
    					error : function(){
  							$("#pBoxContainer"+datatype+" #img_copy_progress").hide();
  							$("#pBoxContainer"+datatype+" #img_copy_cross").show();
  							$("#pBoxContainer"+datatype+" #img_copy_tick").hide();

								_success = false;
    					  $('body').clearQueue();
    					}
	  });
	  return _emsg;
	};
		
</script>