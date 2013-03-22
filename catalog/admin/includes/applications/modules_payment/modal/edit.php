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
#editModule { padding-bottom:20px; }
</style>
<script>
function editModule(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&module=MODULE'); ?>';
  $.getJSON(jsonLink.replace('MODULE', id),
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
                   '    <div class="field-block" id="editModuleFormKeys">'+
                   '    </div>'+
                   '  </form>'+
                   '</fieldset>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_payment_module'); ?>',
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
                var nvp = $("#mEdit").serialize();
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveModule&module=MODULE&BATCH'); ?>'
                $.getJSON(jsonLink.replace('MODULE', id).replace('BATCH', nvp),
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
      $("#editModuleFormKeys").html(data.keys);   
      if ($.template.mediaQuery.isSmallerThan('desktop')) {
        $('.modal').attr('style', 'top:10px !important; left: 25%;  margin-left: -50px;');  
      } 
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('.modal').attr('style', 'top:10px !important; left: 19%;  margin-left: -50px;');  
      }
      if ($.template.mediaQuery.name === 'desktop') {
        $('.modal').attr('style', 'top:10px !important; left: 35%;  margin-left: -50px;');  
      } 
      $(".label").addClass('small-margin-top');   
    }
  );
}
</script>