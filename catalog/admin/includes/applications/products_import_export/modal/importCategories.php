<?php
/*
  $Id: importCategories.php v1.0 2013-12-01 resultsonlyweb $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function importCategories() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  
  var cwizard = false;
  if($('input[name="categories-mapping-wizard"]').is(':checked')){
	cwizard = true;
  }
  var ctype = $('input[name="categories-import-type"]:checked').val();
  var cbackup = false;
  if($('input[name="categories-create-backup"]').is(':checked')){
	cbackgup = true;
  }

	$("#loading").ajaxStart(function(){
		$(this).show();
	}).ajaxComplete(function(){
		$(this).hide();
    });

	var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule()); ?>&action=importCategories&cwizard=CWIZARD&ctype=CTYPE&cbackup=CBACKUP';
	jsonLink = jsonLink.replace('CWIZARD', cwizard).replace('CTYPE', ctype).replace('CBACKUP', cbackup);

	$.ajaxFileUpload({
      url: jsonLink,
	  secureuri:false,
	  fileElementId:'categoriesFile',
	  dataType: 'json',
	  data:{products_import_export: 'test', action:'importCategories'},
	  success: function (data, status) {
		if(typeof(data.error) != 'undefined') {
		  if(data.error != '') {
			$.modal.alert(data.error);
		  } else {
			$.modal.alert(data.msg);
		  }
		} else {
		  if(typeof(data.msg) != 'undefined'){
			if(data.msg != '') {
			  $.modal.alert(data.msg);
			}
		  }
		  if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href',url);
          }
	      console.log(data);
          $.modal({
            content: '<div id="importCategories">'+
                     '  <div id="importCategoriesConfirm">'+
                     '    <p id="importCategoriesConfirmMessage">'+data.total+' <?php echo $lC_Language->get('text_records_imported'); ?>'+
                     '    <p id="importCategoriesConfirmMessage">'+data.matched+' <?php echo $lC_Language->get('text_matched_and_updated'); ?>'+
                     '    <p id="importCategoriesConfirmMessage">'+data.inserted+' <?php echo $lC_Language->get('text_new_records_added'); ?>'+
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
	    }
	  },
	  error: function (data, status, e) {
		alert(e);
	  }
    });
      
  //);
}
</script>