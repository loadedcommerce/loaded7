<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: install.php v1.0 2013-08-08 datazen $
*/
?>
<script>
function installAddon(id, type) {
  mask();
  var po = (type.indexOf('pro+template+pack') != -1) ? true : false;
  if (po == true) {
    $.modal.alert('<?php echo $lC_Language->get('text_available_with_pro'); ?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=installAddon&name=NAME'); ?>'
  $.getJSON(jsonLink.replace('NAME', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        unmask();
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      if (type == 'templates') {
        document.location.href = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'templates'); ?>';
        return;
      } else if (type == 'languages') {
        url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'languages&action=import&language_import=LANG&import_type=replace' ); ?>';
        document.location.href = url.replace('LANG', id);
        return;        
      } else {
        unmask();
        oTable.fnReloadAjax();
        editAddon(id, type);
      }
    }
  );
}
</script>