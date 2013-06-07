<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#editAddon { padding-bottom:20px; }
</style>
<script>
function editAddon(id, name) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&name=NAME'); ?>';
  $.getJSON(jsonLink.replace('NAME', id),
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
          content: '<span id="logo-image"></span>'+
                   '<fieldset class="fieldset fields-list">'+
                   '  <form name="mEdit" id="mEdit" autocomplete="off" action="" method="post">'+
                   '    <div class="field-block relative" id="editAddonFormKeys">'+
                   '    </div>'+
                   '  </form>'+
                   '</fieldset>',
          title: '<?php echo sprintf($lC_Language->get('modal_heading_setup_addon'), 'TITLE'); ?>'.replace('TITLE', name.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } )),
          width: 500,
          scrolling: true,
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
                var nvp = $("#mEdit").serialize();
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveAddon&name=NAME&NVP'); ?>'
                $.getJSON(jsonLink.replace('NAME', id).replace('NVP', nvp),
                  function (rdata) {
                    if (rdata.rpcStatus == -10) { // no session
                      var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                      $(location).attr('href',url);
                    }
                    if (rdata.rpcStatus != 1) {
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
      $("#logo-image").html(data.desc);   
      $("#editAddonFormKeys").html(data.keys);   
      $(".label").addClass('small-margin-top');   
      $.modal.all.centerModal();
    }
  );
}
</script>