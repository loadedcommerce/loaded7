<?php
/*
  $Id: getProductsFile.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function importProducts() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var pwizard = false;
  if($('input[name="products-mapping_wizard"]').is(':checked')){
	pwizard = true;
  }
  var ptype = $('input[name="products-import-type"]:checked').val();
  var pbackup = false;
  if($('input[name="products-create-backup"]').is(':checked')){
	pbackgup = true;
  }
  
	$("#loading").ajaxStart(function(){
		$(this).show();
	}).ajaxComplete(function(){
		$(this).hide();
    });

	var jsonLink = '/admin/rpc.php?products_import_export&action=importProducts&pwizard=PWIZARD&ptype=PTYPE&pbackup=PBACKUP';
	jsonLink = jsonLink.replace('PWIZARD', pwizard).replace('PTYPE', ptype).replace('PBACKUP', pbackup);

	$.ajaxFileUpload({
      url: jsonLink,
	  secureuri:false,
	  fileElementId:'productFile',
	  dataType: 'json',
	  data:{products_import_export: 'test', action:'importProducts'},
	  success: function (data, status) {
		console.log(data);
		if(typeof(data.error) != 'undefined') {
		  if(data.error != '') {
			alert(data.error);
		  } else {
			alert(data.msg);
		  }
		  if(typeof(data.other) != 'undefined'){
			if(data.other != '') {
		      alert(data.other);
			}
		  }
		  if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href',url);
          }
		}
        $.modal({
            content: '<div id="importProducts">'+
                     '  <div id="importProductsConfirm">'+
                     '    <p id="importProductsConfirmMessage">'+data.total+' <?php echo $lC_Language->get('text_records_imported'); ?>'+
                     '    <p id="importProductsConfirmMessage">'+data.matched+' <?php echo $lC_Language->get('text_matched_and_updated'); ?>'+
                     '    <p id="importProductsConfirmMessage">'+data.inserted+' <?php echo $lC_Language->get('text_new_records_added'); ?>'+
                     '    </p>'+
                     '  </div>'+
                     '</div>',
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
	  },
	  error: function (data, status, e) {
		alert(e);
	  }
    });
      
  //);
}
</script>