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
      $.modal({
        content: '<div id="getOptionGroups">'+
                 '  <div id="getOptionGroupsConfirm">'+
                 '    <p id="getOptionGroupsConfirmMessage"><?php echo $lC_Language->get('introduction_get_export'); ?>'+
                 '      <p><a href="'+data.url+'" id="downloadlink" class="hidden" download>Download Export File</a></p>'+
                 '    </p>'+
                 '  </div>'+
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_export_results'); ?>',
        width: 300,
        scrolling: false,
        buttons: {
          'Download Export File': {
            classes:  'green-gradient glossy',
            click:    function(win) {
              $("#downloadlink")[0].click();
			  win.closeModal();
            }
          },
          'Ok': {
            classes:  'blue-gradient glossy',
            click:    function(win) {
              win.closeModal();
            }
          }
        },
        buttonsLowPadding: true
      });
	}
  );
}
</script>
<style>
#getOptionGroupsConfirm { text-align:center; }
</style>