<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: uninstall.php v1.0 2013-08-08 datazen $
*/
?>
<script>
function uninstallAddon(id, name, type) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="uninstallAddon">'+
             '  <div id="uninstallAddonConfirm">'+
             '    <p id="uninstallAddonConfirmMessage"><?php echo $lC_Language->get('introduction_uninstall_addon'); ?>'+
             '      <p><b>' + decodeURI(name.replace(/\+/g, '%20')) + '</b></p>'+
             '    </p>'+
             '  </div>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_uninstall_addon'); ?>',
    width: 300,
    actions: {
      'Close' : {
        color: 'red',
        click: function(win) { win.closeModal(); }
      }
    },
    buttons: {
      '<?php echo $lC_Language->get('button_cancel'); ?>': {
        classes:  'glossy',
        click:    function(win) { win.closeModal(); }
      },
      '<?php echo $lC_Language->get('button_uninstall'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=uninstallAddon&name=NAME'); ?>'
          $.getJSON(jsonLink.replace('NAME', id),
            function (data) {
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                $(location).attr('href',url);
              }
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              } 
              $($.modal.all).closeModal();
              
              var currentType = '<?php echo $_GET['type']; ?>';
              var url = window.location.href;
              var rUrl = '';
              if (currentType == '') {
                rUrl = url + '&type=' + type;
              } else if (currentType != type) {
                rUrl = url.replace('&type=' + currentType, '&type=' + type);
              } 
              window.location.href = rUrl;              
            }
          );
          win.closeModal();
        }
      }
    },
    buttonsLowPadding: true
  });

}
</script>