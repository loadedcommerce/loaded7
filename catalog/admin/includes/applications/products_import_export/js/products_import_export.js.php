<?php
/*
  $Id: products_import_export.js.php v1.0 2013-12-01 reusltsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $modulesArr, $accessArr;
?>
<script>
var pfilename = '';
var cfilename = '';
var ogfilename = '';
var ovfilename = '';
var opfilename = '';
$(document).ready(function(){
	createProductUploader();
	
	/**
	 * CONTENT TAB
	 * 
	/* create the uploader instance for product import */
	function createProductUploader() {
	  var uploader = new qq.FileUploader({
		element: document.getElementById('fileUploaderProductsContainer'),
		action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
		onComplete: function(id, fileName, responseJSON){
		  console.log(responseJSON);
		  pfilename = fileName;
		},
	  });
	}
	createCatUploader();
	
	/**
	 * CONTENT TAB
	 * 
	/* create the uploader instance for product import */
	function createCatUploader() {
	  var uploader = new qq.FileUploader({
		element: document.getElementById('fileUploaderCategoriesContainer'),
		action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
		onComplete: function(id, fileName, responseJSON){
		  console.log(responseJSON);
		  cfilename = fileName;
		},
	  });
	}
	createOptionsGroupsUploader();
	
	/**
	 * CONTENT TAB
	 * 
	/* create the uploader instance for product import */
	function createOptionsGroupsUploader() {
	  var uploader = new qq.FileUploader({
		element: document.getElementById('fileUploaderOptionsGroupsContainer'),
		action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
		onComplete: function(id, fileName, responseJSON){
		  console.log(responseJSON);
		  ogfilename = fileName;
		},
	  });
	}
	createOptionsVarientsUploader();
	
	/**
	 * CONTENT TAB
	 * 
	/* create the uploader instance for product import */
	function createOptionsVarientsUploader() {
	  var uploader = new qq.FileUploader({
		element: document.getElementById('fileUploaderOptionsVarientsContainer'),
		action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
		onComplete: function(id, fileName, responseJSON){
		  console.log(responseJSON);
		  ovfilename = fileName;
		},
	  });
	}
	createOptionsProductsUploader();
	
	/**
	 * CONTENT TAB
	 * 
	/* create the uploader instance for product import */
	function createOptionsProductsUploader() {
	  var uploader = new qq.FileUploader({
		element: document.getElementById('fileUploaderOptionsProductsContainer'),
		action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
		onComplete: function(id, fileName, responseJSON){
		  console.log(responseJSON);
		  opfilename = fileName;
		},
	  });
	}
  });
  function get_filter_total(filter,type) { 
    filter = typeof filter !== 'undefined' ? filter : 'none';
	type = typeof type !== 'undefined' ? type : 'products';
	
    $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule()); ?>&action=getFilterTotal&filter='+filter+'&type='+type,
  	  function (data) {
		$('#'+type+'-filter-total').html(data.total);
		$('#'+type+'-filter-applied').html(filter);
	  }
    );
  }
  $(document).ready(function() {
	get_filter_total('none', 'products');
	get_filter_total('none', 'categories');
	get_filter_total('none', 'options');
    var error = '<?php echo $_SESSION['error']; ?>';
    if (error) {
      var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
      $.modal.alert(errmsg);
    }     
  });
function getOptionProducts() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var ofilter = $('#options-filter').val();
  var ogformat = $('input[name="options-export-format"]:checked').val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getOptionProducts&ofilter=OFILTER&ogformat=OGFORMAT'); ?>';
  $.getJSON(jsonLink.replace('OFILTER', ofilter).replace('OGFORMAT', ogformat),
  	function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
	  console.log(data);
      var downloadurl = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=download&filename=FILENAME'); ?>";
	  console.log(downloadurl);
      $(location).attr('href',downloadurl.replace('FILENAME', data.filename));
	}
  );
}
function getProducts(pgtype) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  pgtype = typeof pgtype !== 'undefined' ? pgtype : 'full';
  var pfilter = $('#products-filter').val();
  var pgformat = $('input[name="products-export-format"]:checked').val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getProducts&pfilter=PFILTER&pgtype=PGTYPE&pgformat=PGFORMAT'); ?>';
  $.getJSON(jsonLink.replace('PFILTER', pfilter).replace('PGTYPE', pgtype).replace('PGFORMAT', pgformat),
  	function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
	  console.log(data);
      var downloadurl = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=download&filename=FILENAME'); ?>";
	  console.log(downloadurl);
      $(location).attr('href',downloadurl.replace('FILENAME', data.filename));
	}
  );
}
function getOptionVariants() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var ofilter = $('#options-filter').val();
  var ogformat = $('input[name="options-export-format"]:checked').val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getOptionVariants&ofilter=OFILTER&ogformat=OGFORMAT'); ?>';
  $.getJSON(jsonLink.replace('OFILTER', ofilter).replace('OGFORMAT', ogformat),
  	function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
	  console.log(data);
      var downloadurl = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=download&filename=FILENAME'); ?>";
	  console.log(downloadurl);
      $(location).attr('href',downloadurl.replace('FILENAME', data.filename));
	}
  );
}
function getOptionGroups() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var ofilter = $('#options-filter').val();
  var ogformat = $('input[name="options-export-format"]:checked').val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getOptionGroups&ofilter=OFILTER&ogformat=OGFORMAT'); ?>';
  $.getJSON(jsonLink.replace('OFILTER', ofilter).replace('OGFORMAT', ogformat),
  	function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
	  console.log(data);
      var downloadurl = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=download&filename=FILENAME'); ?>";
	  console.log(downloadurl);
      $(location).attr('href',downloadurl.replace('FILENAME', data.filename));
	}
  );
}
function getCategories() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var cfilter = $('#categories-filter').val();
  var cgformat = $('input[name="categories-export-format"]:checked').val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCategories&cfilter=CFILTER&cgformat=CGFORMAT'); ?>';
  $.getJSON(jsonLink.replace('CFILTER', cfilter).replace('CGFORMAT', cgformat),
  	function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
	  console.log(data);
      var downloadurl = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=download&filename=FILENAME'); ?>";
	  console.log(downloadurl);
      $(location).attr('href',downloadurl.replace('FILENAME', data.filename));
	}
  );
}
</script>