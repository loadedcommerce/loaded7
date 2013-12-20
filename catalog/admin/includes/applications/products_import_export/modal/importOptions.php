<?php
/*
  $Id: importOptions.php v1.0 2013-12-01 resultsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function importOptions() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var owizard = false;
  if($('input[name="options-mapping-wizard"]').is(':checked')){
	owizard = true;
  }
  var otype = $('input[name="options-import-type"]:checked').val();
  var obackup = false;
  if($('input[name="options-create-backup"]').is(':checked')){
	obackgup = true;
  }
	
  var data = {};
	
  if($('#optionsGroupsFile').val() || $('#optionsVariantsFile').val() || $('#optionsProductsFile').val()){
    $("#loading").show();
	importOptionGroups(owizard, otype, obackup, data);
  } else {
    $.modal.alert('Please select a file to import');
  }
}
function importOptionGroups(owizard, otype, obackup, data){
	if($('#optionsGroupsFile').val()){
		var OGjsonLink = '/admin/rpc.php?products_import_export&action=importOptionGroups&owizard=OWIZARD&otype=OTYPE&obackup=OBACKUP';
		OGjsonLink = OGjsonLink.replace('OWIZARD', owizard).replace('OTYPE', otype).replace('OBACKUP', obackup);
	
		$.ajaxFileUpload({
		  url: OGjsonLink,
		  secureuri:false,
		  fileElementId:'optionsGroupsFile',
		  dataType: 'json',
		  data:{products_import_export: 'test', action:'importOptionGroups'},
		  success: function (OGdata, OGstatus) {
			if(typeof(OGdata.error) != 'undefined') {
			  if(OGdata.error != '') {
				$.modal.alert(OGdata.error);
			  } else {
				$.modal.alert(OGdata.msg);
			  }
			} else {
			  if(typeof(OGdata.msg) != 'undefined'){
				if(OGdata.msg != '') {
				  $.modal.alert(OGdata.msg);
				}
			  }
			  if (OGdata.rpcStatus == -10) { // no session
				var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
				$(location).attr('href',url);
			  }
			  console.log(OGdata);
			  data.OGdata = OGdata;
			  importOptionVariants(owizard, otype, obackup, data);
			}
		  },
		  error: function (OGdata, OGstatus, OGe) {
			alert(OGe);
		  }
		});
	} else {
		importOptionVariants(owizard, otype, obackup, data);
	}
}
function importOptionVariants(owizard, otype, obackup, data){
	if($('#optionsVariantsFile').val()){
		var OVjsonLink = '/admin/rpc.php?products_import_export&action=importOptionVariants&owizard=OWIZARD&otype=OTYPE&obackup=OBACKUP';
		OVjsonLink = OVjsonLink.replace('OWIZARD', owizard).replace('OTYPE', otype).replace('OBACKUP', obackup);
	
		$.ajaxFileUpload({
		  url: OVjsonLink,
		  secureuri:false,
		  fileElementId:'optionsVariantsFile',
		  dataType: 'json',
		  data:{products_import_export: 'test', action:'importOptionGroups'},
		  success: function (OVdata, OVstatus) {
			if(typeof(OVdata.error) != 'undefined') {
			  if(OVdata.error != '') {
				$.modal.alert(OVdata.error);
			  } else {
				$.modal.alert(OVdata.msg);
			  }
			} else {
			  if(typeof(OVdata.msg) != 'undefined'){
				if(OVdata.msg != '') {
				  $.modal.alert(OVdata.msg);
				}
			  }
			  if (OVdata.rpcStatus == -10) { // no session
				var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
				$(location).attr('href',url);
			  }
			  console.log(OVdata);
			  data.OVdata = OVdata;
			  importOptionProducts(owizard, otype, obackup, data);
			}
		  },
		  error: function (OVdata, OVstatus, OVe) {
			alert(OVe);
		  }
		});
	} else {
		importOptionProducts(owizard, otype, obackup, data);
	}
}
function importOptionProducts(owizard, otype, obackup, data){
	if($('#optionsProductsFile').val()){
		var OPjsonLink = '/admin/rpc.php?products_import_export&action=importOptionProducts&owizard=OWIZARD&otype=OTYPE&obackup=OBACKUP';
		OPjsonLink = OPjsonLink.replace('OWIZARD', owizard).replace('OTYPE', otype).replace('OBACKUP', obackup);
	
		$.ajaxFileUpload({
		  url: OPjsonLink,
		  secureuri:false,
		  fileElementId:'optionsProductsFile',
		  dataType: 'json',
		  data:{products_import_export: 'test', action:'importOptionProducts'},
		  success: function (OPdata, OPstatus) {
			if(typeof(OPdata.error) != 'undefined') {
			  if(OPdata.error != '') {
				$.modal.alert(OPdata.error);
			  } else {
				$.modal.alert(OPdata.msg);
			  }
			} else {
			  if(typeof(OPdata.msg) != 'undefined'){
				if(OPdata.msg != '') {
				  $.modal.alert(OPdata.msg);
				}
			  }
			  if (OPdata.rpcStatus == -10) { // no session
				var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
				$(location).attr('href',url);
			  }
			  console.log(OPdata);
			  data.OPdata = OPdata;
			  reportImportOptions(owizard, otype, obackup, data);
			}
		  },
		  error: function (OPdata, OPstatus, OPe) {
			alert(OPe);
		  }
		});
	} else {
		reportImportOptions(owizard, otype, obackup, data);
	}
}
function reportImportOptions(owizard, otype, obackup, data){
	var mcontent = '';
	if(data.OGdata){
		mcontent += '	<p id="importOptionsConfirmTitle">Options Groups:</p>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OGdata.total+' <?php echo $lC_Language->get('text_records_imported'); ?>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OGdata.matched+' <?php echo $lC_Language->get('text_matched_and_updated'); ?>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OGdata.inserted+' <?php echo $lC_Language->get('text_new_records_added'); ?>';
	}
	if(data.OVdata){
		mcontent += '	<p id="importOptionsConfirmTitle">Options Variants:</p>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OVdata.total+' <?php echo $lC_Language->get('text_records_imported'); ?>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OVdata.matched+' <?php echo $lC_Language->get('text_matched_and_updated'); ?>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OVdata.inserted+' <?php echo $lC_Language->get('text_new_records_added'); ?>';
	}
	if(data.OPdata){
		mcontent += '	<p id="importOptionsConfirmTitle">Options to Products:</p>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OPdata.total+' <?php echo $lC_Language->get('text_records_imported'); ?>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OPdata.matched+' <?php echo $lC_Language->get('text_matched_and_updated'); ?>'+
				   '    <p id="importOptionsConfirmMessage">'+data.OPdata.inserted+' <?php echo $lC_Language->get('text_new_records_added'); ?>';
	}
  	$.modal({
		content: mcontent,
		title: '<?php echo $lC_Language->get('modal_heading_import_results'); ?>',
		width: 300,
		scrolling: false,
		buttons: {
		  'Ok': {
			classes:  'green-gradient glossy',
			click:    function(win) {
			  win.closeModal();
			}
		  }
		},
		buttonsLowPadding: true
	});
}
</script>