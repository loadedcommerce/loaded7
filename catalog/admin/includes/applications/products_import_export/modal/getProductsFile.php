<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products_import_export.php v1.0 2013-12-03 resultsonlyweb $
*/
?>
<script>
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
        $(location).attr('href', url);
      }
      console.log(data);
      $.modal({
        content: '<div id="getProducts">'+
                 '  <div id="getProductsConfirm">'+
                 '    <p id="getProductsConfirmMessage"><?php echo $lC_Language->get('introduction_get_export'); ?>'+
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
  #getProductsConfirm { text-align:center; }
</style>