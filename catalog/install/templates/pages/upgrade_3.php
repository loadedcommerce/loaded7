<?php
/**
  @package    catalog::install::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: upgrade_3.php v1.0 2013-08-08 datazen $
*/
switch ($_POST['upgrade_method']) {
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
		//echo '<pre>';
    //print_r($_POST);
		//echo '</pre>';
    //die();
	}
	break;
}
?>
<style scoped="scoped">
	.importstatus { 
		height: 20px;
	}
	.step_err_msg {
		margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;
	} 
</style>
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
      <p class="message icon-warning red-gradient align-center">   
        <span id="mBoxContents"></span>
      </p> 
    </div>
    <div id="mBoxSuccess" style="display:none; padding:0px 20px 10px"> 
      <p class="message green-gradient align-center">   
        <span id="mBoxSuccessContents"></span>
      </p> 
    </div>
    <div class="with-padding">  
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CATEGORIES" class="label"><b><?php echo $lC_Language->get('param_import_categories'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_categories" style="height:25px; padding-top:5px;">  
          <img style="display:none;" id="img_copy_tick_categories" class="tick" src="images/tick.png" align="right" />
          <img id="img_copy_progress_categories" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_categories" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_categories" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_categories"></span>
          </p> 
        </div>      
      </div>  
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CATEGORIES_DESCRIPTION" class="label"><b><?php echo $lC_Language->get('param_import_categories_description'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_categories_description" style="height:25px; padding-top:5px;">  
          <img style="display:none;" id="img_copy_tick_categories_description" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_categories_description" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_categories_description" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_categories_description" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_categories_description"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CUSTOMERS" class="label"><b><?php echo $lC_Language->get('param_import_customers'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_customers" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_customers" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_customers" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_customers" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_customers" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_customers"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ADDRESS_BOOK" class="label"><b><?php echo $lC_Language->get('param_import_address_book'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_address_book" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_address_book" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_address_book" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_address_book" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_address_book" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_address_book"></span>
          </p> 
        </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CUSTOMER_GROUPS" class="label"><b><?php echo $lC_Language->get('param_import_customer_groups'); ?></b></label>
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_customer_groups" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_customer_groups" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_customer_groups" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_customer_groups" class="cross" src="images/cross.png" align="right" />
    	    </p> 
    	  </div>      
    	  <div id="eBox_customer_groups" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
    	    <p class="message icon-warning red-gradient">   
    	      <span id="eBoxContents_customer_groups"></span>
    	    </p> 
    	  </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_PRODUCTS" class="label"><b><?php echo $lC_Language->get('param_import_products'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_products" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_products" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_products" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_products" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_products" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_products"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_PRODUCTS_DESCRIPTION" class="label"><b><?php echo $lC_Language->get('param_import_products_description'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_products_description" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_products_description" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_products_description" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_products_description" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_products_description" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_products_description"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_PRODUCTS_NOTIFICATIONS" class="label"><b><?php echo $lC_Language->get('param_import_products_notifications'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_products_notifications" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_products_notifications" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_products_notifications" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_products_notifications" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_products_notifications" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_products_notifications"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_PRODUCTS_TO_CATEGORIES" class="label"><b><?php echo $lC_Language->get('param_import_products_to_categories'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_products_to_categories" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_products_to_categories" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_products_to_categories" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_products_to_categories" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_products_to_categories" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_products_to_categories"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_MANUFACTURERS" class="label"><b><?php echo $lC_Language->get('param_import_manufacturers'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_manufacturers" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_manufacturers" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_manufacturers" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_manufacturers" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_manufacturers" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_manufacturers"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_MANUFACTURERS_INFO" class="label"><b><?php echo $lC_Language->get('param_import_manufacturers_info'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_manufacturers_info" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_manufacturers_info" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_manufacturers_info" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_manufacturers_info" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_manufacturers_info" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_manufacturers_info"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_REVIEWS" class="label"><b><?php echo $lC_Language->get('param_import_reviews'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_reviews" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_reviews" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_reviews" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_reviews" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_reviews" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_reviews"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_SPECIALS" class="label"><b><?php echo $lC_Language->get('param_import_specials'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_specials" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_specials" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_specials" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_specials" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_specials" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_specials"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ATTRIBUTES" class="label"><b><?php echo $lC_Language->get('param_import_attributes'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_attributes" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_attributes" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_attributes" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_attributes" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_attributes" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_attributes"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_FEATURED" class="label"><b><?php echo $lC_Language->get('param_import_featured'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_featured" style="height:25px; padding-top:5px;">   
          <img style="display:none;" id="img_copy_tick_featured" class="tick" src="images/tick.png" align="right" />
          <img style="display:none;" id="img_copy_progress_featured" class="progress" src="images/ajax-loader-1.gif" align="right" />
          <img style="display:none;" id="img_copy_cross_featured" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_featured" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_featured"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS" class="label"><b><?php echo $lC_Language->get('param_import_orders'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_orders" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_orders" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_orders" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_orders" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_orders" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_orders"></span>
          </p> 
        </div>      
      </div>       
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS_PRODUCTS" class="label"><b><?php echo $lC_Language->get('param_import_orders_products'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_orders_products" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_orders_products" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_orders_products" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_orders_products" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_orders_products" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_orders_products"></span>
          </p> 
        </div>      
      </div>       
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS_PRODUCTS_DOWNLOAD" class="label"><b><?php echo $lC_Language->get('param_import_orders_products_download'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_orders_products_download" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_orders_products_download" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_orders_products_download" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_orders_products_download" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_orders_products_download" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_orders_products_download"></span>
          </p> 
        </div>      
      </div>       
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS_STATUS" class="label"><b><?php echo $lC_Language->get('param_import_orders_status'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_orders_status" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_orders_status" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_orders_status" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_orders_status" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_orders_status" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_orders_status"></span>
          </p> 
        </div>      
      </div>       
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS_STATUS_HISTORY" class="label"><b><?php echo $lC_Language->get('param_import_orders_status_history'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_orders_status_history" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_orders_status_history" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_orders_status_history" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_orders_status_history" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_orders_status_history" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_orders_status_history"></span>
          </p> 
        </div>      
      </div>       
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_ORDERS_TOTAL" class="label"><b><?php echo $lC_Language->get('param_import_orders_total'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_orders_total" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_orders_total" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_orders_total" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_orders_total" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_orders_total" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_orders_total"></span>
          </p> 
        </div>      
      </div>
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CDS" class="label"><b><?php echo $lC_Language->get('param_import_cds'); ?></b></label>
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_cds" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_cds" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_cds" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_cds" class="cross" src="images/cross.png" align="right" />
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
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_administrators" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_administrators" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_administrators" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_administrators" class="cross" src="images/cross.png" align="right" />
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
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_newsletter" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_newsletter" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_newsletter" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_newsletter" class="cross" src="images/cross.png" align="right" />
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
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_banners" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_banners" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_banners" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_banners" class="cross" src="images/cross.png" align="right" />
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
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_configuration" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_configuration" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_configuration" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_configuration" class="cross" src="images/cross.png" align="right" />
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
    	  <div style="display:block; padding:0px 20px 0px 0px"> 
    	    <p id="pBoxContainer_coupons" style="height:25px; padding-top:5px;">   
    			  <img style="display:none;" id="img_copy_tick_coupons" class="tick" src="images/tick.png" align="right" />
    			  <img style="display:none;" id="img_copy_progress_coupons" class="progress" src="images/ajax-loader-1.gif" align="right" />
    			  <img style="display:none;" id="img_copy_cross_coupons" class="cross" src="images/cross.png" align="right" />
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
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_taxclasses" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_taxclasses" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_taxclasses" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_taxclasses" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_taxclasses" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_taxclasses"></span>
          </p> 
        </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_LANGUAGES" class="label"><b><?php echo $lC_Language->get('param_import_languages'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_languages" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_languages" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_languages" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_languages" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_languages" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_languages"></span>
          </p> 
        </div>      
      </div> 
      <div class="field-block button-height large-margin-left large-margin-right">
        <label for="IMPORT_CURRENCIES" class="label"><b><?php echo $lC_Language->get('param_import_currencies'); ?></b></label>
        <div style="display:block; padding:0px 20px 0px 0px"> 
          <p id="pBoxContainer_currencies" style="height:25px; padding-top:5px;">   
            <img style="display:none;" id="img_copy_tick_currencies" class="tick" src="images/tick.png" align="right" />
            <img style="display:none;" id="img_copy_progress_currencies" class="progress" src="images/ajax-loader-1.gif" align="right" />
            <img style="display:none;" id="img_copy_cross_currencies" class="cross" src="images/cross.png" align="right" />
          </p> 
        </div>      
        <div id="eBox_currencies" style="display:none; margin: 0px 0px 0px -190px; padding: 0px 10px 10px 0px;"> 
          <p class="message icon-warning red-gradient">   
            <span id="eBoxContents_currencies"></span>
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
	$('#pBoxContents').html('UPGRADE IN PROGRESS');
  $('#pBox').show();
  var _rslt = ""; 
  var _err = false;
  var _errmsg = "";
  
  setTimeout(function() {   
    $('body').queue(function() {
      $('#img_copy_progress_categories').show();
      setTimeout(function() {
        _rslt = doImport('_categories'); 
        if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
          $('#eBoxContents_categories').html(_rslt);
          $('#eBox_categories').show();
        }
        
        $('#img_copy_progress_categories_description').show();
        setTimeout(function() {
          _rslt = doImport('_categories_description'); 
          if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
            $('#eBoxContents_categories_description').html(_rslt);
            $('#eBox_categories_description').show();
          }
            
          $('#img_copy_progress_customers').show();
          setTimeout(function() {
            _rslt = doImport('_customers'); 
            if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
              $('#eBoxContents_customers').html(_rslt);
              $('#eBox_customers').show();
            }
            
            $('#img_copy_progress_address_book').show();
            setTimeout(function() {
              _rslt = doImport('_address_book'); 
              if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                $('#eBoxContents_address_book').html(_rslt);
                $('#eBox_address_book').show();
              }

              $('#img_copy_progress_customer_groups').show();
              setTimeout(function() {
                _rslt = doImport('_customer_groups'); 
                if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                  $('#eBoxContents_customer_groups').html(_rslt);
                  $('#eBox_customer_groups').show();
                }
            
                $('#img_copy_progress_products').show();
                setTimeout(function() {
                  _rslt = doImport('_products'); 
                  if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                    $('#eBoxContents_products').html(_rslt);
                    $('#eBox_products').show();
                  }
            
                  $('#img_copy_progress_products_description').show();
                  setTimeout(function() {
                    _rslt = doImport('_products_description'); 
                    if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                      $('#eBoxContents_products_description').html(_rslt);
                      $('#eBox_products_description').show();
                    }
            
                    $('#img_copy_progress_products_notifications').show();
                    setTimeout(function() {
                      _rslt = doImport('_products_notifications'); 
                      if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                        $('#eBoxContents_products_notifications').html(_rslt);
                        $('#eBox_products_notifications').show();
                      }
            
                      $('#img_copy_progress_products_to_categories').show();
                      setTimeout(function() {
                        _rslt = doImport('_products_to_categories'); 
                        if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                          $('#eBoxContents_products_to_categories').html(_rslt);
                          $('#eBox_products_to_categories').show();
                        }
            
                        $('#img_copy_progress_manufacturers').show();
                        setTimeout(function() {
                          _rslt = doImport('_manufacturers'); 
                          if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                            $('#eBoxContents_manufacturers').html(_rslt);
                            $('#eBox_manufacturers').show();
                          }
            
                          $('#img_copy_progress_manufacturers_info').show();
                          setTimeout(function() {
                            _rslt = doImport('_manufacturers_info'); 
                            if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                              $('#eBoxContents_manufacturers_info').html(_rslt);
                              $('#eBox_manufacturers_info').show();
                            }
            
                            $('#img_copy_progress_reviews').show();
                            setTimeout(function() {
                              _rslt = doImport('_reviews'); 
                              if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                $('#eBoxContents_reviews').html(_rslt);
                                $('#eBox_reviews').show();
                              }
            
                              $('#img_copy_progress_specials').show();
                              setTimeout(function() {
                                _rslt = doImport('_specials'); 
                                if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                  $('#eBoxContents_specials').html(_rslt);
                                  $('#eBox_specials').show();
                                }

                                $('#img_copy_progress_attributes').show();
                                setTimeout(function() {
                                  _rslt = doImport('_attributes'); 
                                  if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                    $('#eBoxContents_attributes').html(_rslt);
                                    $('#eBox_attributes').show();
                                  }
                          
                                  $('#img_copy_progress_featured').show();
                                  setTimeout(function() {
                                    _rslt = doImport('_featured'); 
                                    if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                      $('#eBoxContents_featured').html(_rslt);
                                      $('#eBox_featured').show();
                                    }
                                    
                                    $('#img_copy_progress_orders').show();
                                    setTimeout(function() {
                                      _rslt = doImport('_orders'); 
                                      if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                        $('#eBoxContents_orders').html(_rslt);
                                        $('#eBox_customer_orders').show();
                                      } 
                                    
                                      $('#img_copy_progress_orders_products').show();
                                      setTimeout(function() {
                                        _rslt = doImport('_orders_products'); 
                                        if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                          $('#eBoxContents_orders_products').html(_rslt);
                                          $('#eBox_customer_orders_products').show();
                                        }
                                    
                                        $('#img_copy_progress_orders_products_download').show();
                                        setTimeout(function() {
                                          _rslt = doImport('_orders_products_download'); 
                                          if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                            $('#eBoxContents_orders_products_download').html(_rslt);
                                            $('#eBox_customer_orders_products_download').show();
                                          }
                                    
                                          $('#img_copy_progress_orders_status').show();
                                          setTimeout(function() {
                                            _rslt = doImport('_orders_status'); 
                                            if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                              $('#eBoxContents_orders_status').html(_rslt);
                                              $('#eBox_customer_orders_status').show();
                                            }
                                    
                                            $('#img_copy_progress_orders_status_history').show();
                                            setTimeout(function() {
                                              _rslt = doImport('_orders_status_history'); 
                                              if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                $('#eBoxContents_orders_status_history').html(_rslt);
                                                $('#eBox_customer_orders_status_history').show();
                                              }
                                    
                                              $('#img_copy_progress_orders_total').show();
                                              setTimeout(function() {
                                                _rslt = doImport('_orders_total'); 
                                                if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                  $('#eBoxContents_orders_total').html(_rslt);
                                                  $('#eBox_customer_orders_total').show();
                                                }
                                       
                                                $('#img_copy_progress_cds').show();
                                                setTimeout(function() {
                                                  _rslt = doImport('_cds'); 
                                                  if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                    $('#eBoxContents_cds').html(_rslt);
                                                    $('#eBox_customer_cds').show();
                                                  }

                                                  $('#img_copy_progress_administrators').show();
                                                  setTimeout(function() {
                                                    _rslt = doImport('_administrators'); 
                                                    if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                      $('#eBoxContents_administrators').html(_rslt);
                                                      $('#eBox_customer_administrators').show();
                                                    }
                                                    
                                                    $('#img_copy_progress_newsletter').show();
                                                    setTimeout(function() {
                                                      _rslt = doImport('_newsletter'); 
                                                      if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                        $('#eBoxContents_newsletter').html(_rslt);
                                                        $('#eBox_customer_newsletter').show();
                                                      }
                                                      
                                                      $('#img_copy_progress_banners').show();
                                                      setTimeout(function() {
                                                        _rslt = doImport('_banners'); 
                                                        if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                          $('#eBoxContents_banners').html(_rslt);
                                                          $('#eBox_customer_banners').show();
                                                        }

                                                        $('#img_copy_progress_configuration').show();
                                                        setTimeout(function() {
                                                          _rslt = doImport('_configuration'); 
                                                          if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                            $('#eBoxContents_configuration').html(_rslt);
                                                            $('#eBox_customer_configuration').show();
                                                          }
                                                          
                                                          $('#img_copy_progress_coupons').show();
                                                          setTimeout(function() {
                                                            _rslt = doImport('_coupons'); 
                                                            if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                              $('#eBoxContents_coupons').html(_rslt);
                                                              $('#eBox_customer_coupons').show();
                                                            }
                                                            
                                                            $('#img_copy_progress_taxclasses').show();
                                                            setTimeout(function() {
                                                              _rslt = doImport('_taxclasses'); 
                                                              if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                                $('#eBoxContents_taxclasses').html(_rslt);
                                                                $('#eBox_customer_taxclasses').show();
                                                              }
                                                              
                                                              $('#img_copy_progress_languages').show();
                                                              setTimeout(function() {
                                                                _rslt = doImport('_languages'); 
                                                                if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                                  $('#eBoxContents_languages').html(_rslt);
                                                                  $('#eBox_languages').show();
                                                                }
                                                                
                                                                $('#img_copy_progress_currencies').show();
                                                                setTimeout(function() {
                                                                  _rslt = doImport('_currencies'); 
                                                                  if (_rslt === "") { } else { _err = true; _errmsg = _errmsg + '<p>' + _rslt; 
                                                                    $('#eBoxContents_currencies').html(_rslt);
                                                                    $('#eBox_currencies').show();
                                                                  }
                                                                
                                                                  // DONE
                                                                  $('#pBox').hide();
                                                                  
                                                                  if (_err === false) {
                                                                    $('#mBoxSuccessContents').html('UPGRADE COMPLETE');
                                                                    $('#mBoxSuccess').show();

                                                                    $("#upgradeForm").attr("action", "upgrade.php?step=4");
                                                                    $('#btn_continue').show();
                                                                    $('#btn_retry').hide();
                                                                  } else {
                                                                    $('#mBoxContents').html('<?php echo $lC_Language->get('upgrade_step3_page_errfound'); ?>');
                                                                    $('#mBox').show();

                                                                    $("#upgradeForm").attr("action", "upgrade.php?step=3");
                                                                    $('#btn_continue').hide();
                                                                    $('#btn_retry').show();
                                                                  }
                                                                  
                                                                  $('#buttonContainer').show();                                  
                                                      
                                                                }, 3000);
                                                              }, 3000);
                                                            }, 3000);  
                                                          }, 3000);                                 
                                                        }, 3000);                              
                                                      }, 3000);
                                                    }, 3000);
                                                  }, 3000);  
                                                }, 3000);                                 
                                              }, 3000);                              
                                            }, 3000); 
                                          }, 3000);                           
                                        }, 3000);                        
                                      }, 3000);                      
                                    }, 3000);                     
                                  }, 3000);                  
                                }, 3000);                
                              }, 3000);                
                            }, 3000);                
                          }, 3000);                
                        }, 3000);                
                      }, 3000);                
                    }, 3000);                
                  }, 3000);                
                }, 3000);                
              }, 3000);             
            }, 3000);             
          }, 3000);             
        }, 3000);          
      }, 3000);
    });
  }, 3000);
});
  
var doImport = function(datatype) {
  var _success = false;
  var _emsg = "";
  $.ajax({
    url: "rpc.php?action=import"+datatype,
    type: 'POST',
    data : $("form").serialize(),
    async : false, 
    cache: false,
    beforeSend : function() { 
      $("#img_copy_progress"+datatype).show();
      $("#img_copy_cross"+datatype).hide();
      $("#img_copy_tick"+datatype).hide();
    },
    success : function(data) {                          
      $("#img_copy_progress"+datatype).hide();
      $("#img_copy_cross"+datatype).hide();
      $("#img_copy_tick"+datatype).show();

      var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(data);
      result.shift();

      if (result[0] == '1') {
        $("#img_copy_progress"+datatype).hide();
        $("#img_copy_cross"+datatype).hide();
        $("#img_copy_tick"+datatype).show();
      } else {
        $("#img_copy_progress"+datatype).hide();
        $("#img_copy_cross"+datatype).show();
        $("#img_copy_tick"+datatype).hide();

        _success = false;
        _emsg = result[1];
        $('body').clearQueue();
      }

      // Que up next ajax call
      $('body').dequeue();
    },
    error : function(){
      $("#img_copy_progress"+datatype).hide();
      $("#img_copy_cross"+datatype).show();
      $("#img_copy_tick"+datatype).hide();

      _success = false;
      $('body').clearQueue();
    }
  });
  return _emsg;
};
</script>