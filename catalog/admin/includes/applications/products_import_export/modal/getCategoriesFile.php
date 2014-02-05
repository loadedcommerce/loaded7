<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: getCategoriesFile.php v1.0 2013-12-03 resultsonlyweb $
*/
?>
<script>
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
        $(location).attr('href', url);
      }
	    console.log(data);
      $.modal({
        content: '<div id="getCategories">'+
                 '  <div id="getCategoriesConfirm">'+
                 '    <p id="getCategoriesConfirmMessage"><?php echo $lC_Language->get('introduction_get_export'); ?>'+
                 '      <p><a href="' + data.url + '" id="downloadlink" class="hidden" download><?php echo $lC_Language->get('download_export_file'); ?></a></p>'+
                 '    </p>'+
                 '  </div>'+
                 '</div>',
        title: '<?php echo $lC_Language->get('modal_heading_export_results'); ?>',
        width: 300,
        scrolling: false,
        buttons: {
          'Download Export File': {
            classes: 'green-gradient glossy',
            click: function(win) {
              $("#downloadlink")[0].click();
			        win.closeModal();
            }
          },
          'Ok': {
            classes: 'blue-gradient glossy',
            click: function(win) {
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
<style scoped="scoped">
#getCategoriesConfirm { text-align:center; }
</style>