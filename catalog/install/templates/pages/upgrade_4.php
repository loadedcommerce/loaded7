<?php
/**
  @package    catalog::install::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upgrade_4.php v1.0 2013-08-08 datazen $
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
<style>
.importstatus { height: 20px; }
</style>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/xmlhttp.js"></script>
<script language="javascript" type="text/javascript" src="../includes/javascript/xmlhttp/autocomplete.js"></script>
<script language="javascript" type="text/javascript">
<!--//
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
//-->
</script>
<form name="upgradeForm" id="updateForm" action="upgrade.php?step=5" method="post" class="block wizard-enabled">
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
    <legend class="legend">Server</legend>
    <div class="field-block margin-bottom" style="padding-left:20px;">
      <h4><?php echo $lC_Language->get('upgrade_step4_page_title'); ?></h4>
      <p><?php echo $lC_Language->get('upgrade_step4_page_desc'); ?></p>
    </div>
    
    <div id="pBox" style="display:none; padding: 0px 20px 10px;">
      <p class="message blue-gradient align-center">  
        <span class="stripes animated"></span>
        <span id="pBoxContents"></span>
        <span class="block-arrow bottom"></span>
      </p>      
    </div>
    
    <div id="mBox" style="display:none; padding:0px 20px 0px"> 
      <p class="message icon-warning red-gradient">   
        <span id="mBoxContents"></span>
      </p> 
    </div>      
    
    <div id="mBoxSuccess" style="display:none; padding:0px 20px 0px"> 
      <p class="message green-gradient">   
        <span id="mBoxSuccessContents"></span>
      </p> 
    </div>
    
    <div class="with-padding">      
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_IMAGES" class="label"><b><?php echo $lC_Language->get('upgrade_step4_label_import_product_images'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 10px 0px"> 
    	    <p id="pBoxContainer_images" style="height:25px; padding-top:5px;">   
    		  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    		  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    		  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
      </div>
      
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CATEGORY_IMAGES" class="label"><b><?php echo $lC_Language->get('upgrade_step4_label_import_categ_images'); ?></b></label>
    	  <div id="mBox" style="display:block; padding:0px 20px 10px 0px"> 
    	    <p id="pBoxContainer_category_images" style="height:25px; padding-top:5px;">   
    		  <img id="img_copy_tick" class="tick" src="images/tick.png" align="right" />
    		  <img id="img_copy_progress" class="progress" src="images/ajax-loader-1.gif" align="right" />
    		  <img id="img_copy_cross" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
      </div>
    </div>
    <div id="buttonContainer" class="large-margin-top margin-right" style="float:right">
      <a id="btn_continue" href="javascript://" onclick="$('#mBox').hide(); $('#pBox').hide();$('#updateForm').submit();" class="button">
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

	$(document).ready(function() {

		$('.tick').hide();
		$('.progress').hide();
		$('.cross').hide();

    var 
        processes = [
            {
                type: '_images'
            },            
            {
                type: '_category_images'
            }
        ];
        
		$('#pBoxContents').html('IMAGE IMPORT IN PROGRESS');
    $('#pBox').show();
    var _rslt = true; var _err = false;
    $.each(processes, function(i, data) {
			$('body').queue(function() {
        _rslt = doImport(data['type']);
        if(_rslt){}
        else { _err = true; }
      });    	
    });    
    $('#pBox').hide();
	  $('#mBoxSuccess').hide();
    
    if(_err === false){
			$('#mBoxSuccessContents').html('IMAGE IMPORT COMPLETE');
    	$('#mBox').hide();
    	$('#mBoxSuccess').show();
    }
    else{
			$('#mBoxContents').html('<?php echo $lC_Language->get('upgrade_step4_page_errfound'); ?>');
	    $('#mBoxSuccess').hide();
    	$('#mBox').show();
    } 
		
	});

	var doImport = function(datatype){
		var _success = true;
	  $.ajax({
 		 					url: "rpc.php?action=import"+datatype,
 		 					type: 'POST',
 		 					data : $("form").serialize(),
 		 					async : false, 
  						cache: false,
  						beforeSend : function() { 
  							console.log("rpc.php?action=upgrade"+datatype);
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
	  return _success;
	};
		
</script>