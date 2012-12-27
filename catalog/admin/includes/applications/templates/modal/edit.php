<?php
/*
  $Id: edit.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#editTemplate { padding-bottom:20px; }
</style>
<script>
function editTemplate(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&template=TEMPLATE'); ?>';
  $.getJSON(jsonLink.replace('TEMPLATE', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="editTemplate">'+
                   '  <div id="editTemplateForm">'+
                   '    <form name="tEdit" id="tEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_template'); ?></p>'+
                   '      <div id="editTemplateFormKeys"></div>'+
                   '      <div id="editTemplateFormDefault"></div>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_template'); ?>',
          width: 500,
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
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var nvp = $("#tEdit").serialize();
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveTemplate&template=TEMPLATE&BATCH'); ?>'
                $.getJSON(jsonLink.replace('TEMPLATE', id).replace('BATCH', nvp),
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
      $("#editTemplateFormKeys").html(data.keys);
      var defaultTemplate = '<?php echo DEFAULT_TEMPLATE; ?>';
      if (data.code != defaultTemplate) {
        $("#editTemplateFormDefault").html('<table border="0" width="100%" cellspacing="0" cellpadding="2"><tr><td width="30%"><?php echo '<b>' . $lC_Language->get('field_set_as_default') . '</b>'; ?></td><td width="70%"><?php echo lc_draw_checkbox_field('default'); ?></td></tr></table>');
      }
      if ($.template.mediaQuery.isSmallerThan('desktop')) {
        $('.modal').attr('style', 'top:10px !important; left: 25%;  margin-left: -50px;');  
      } 
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('.modal').attr('style', 'top:10px !important; left: 19%;  margin-left: -50px;');  
      }      
    }
  );
}
</script>