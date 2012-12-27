<?php
/*
  $Id: delete.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function deleteOrder(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 4) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
    content: '<div id="deleteOrder">'+
             '  <div id="deleteConfirm">'+
             '    <form name="oDelete" id="oDelete" action="" method="post">'+
             '      <p id="deleteConfirmMessage"><?php echo $lC_Language->get('introduction_delete_order'); ?>'+
             '        <p><b>' + decodeURI(name.replace(/\+/g, '%20')) + '</b></p>'+
             '        <p><label for="restock" class="label"><?php echo $lC_Language->get('field_restock_product_quantity'); ?></label><?php echo '&nbsp;' . lc_draw_checkbox_field('restock', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
             '      </p>'+
             '    </form>'+
             '  </div>'+
             '</div>',
    title: '<?php echo $lC_Language->get('modal_heading_delete_order'); ?>',
    width: 300,
    scrolling: false,
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
      '<?php echo $lC_Language->get('button_delete'); ?>': {
        classes:  'blue-gradient glossy',
        click:    function(win) {
          var nvp = $("#oDelete").serialize(); 
          var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteOrder&oid=OID&STOCK'); ?>';   
          $.getJSON(jsonLink.replace('OID', id).replace('STOCK', nvp),
            function (data) {
              if (data.rpcStatus == -10) { // no session
                var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                $(location).attr('href',url);
              }
              if (data.rpcStatus != 1) {
                $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                return false;
              }
              oTable.fnReloadAjax();
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