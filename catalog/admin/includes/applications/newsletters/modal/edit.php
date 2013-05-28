<?php
/*
  $Id: new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#editNewsletter { padding-bottom:20px; }
</style>
<script>
function editNewsletter(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&nid=NID'); ?>';  
  $.getJSON(jsonLink.replace('NID', id),
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
          content: '<div id="editNewsletter">'+
                   '  <div id="editNewsletterForm">'+
                   '    <form name="updateEntry" id="updateEntry" action="" method="post">'+ 
                   '      <p><?php echo $lC_Language->get('introduction_edit_newsletter'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="module" class="label"><?php echo $lC_Language->get('field_module'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('module', null, null, 'id="editModule" class="select" style="width:46%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '        <?php echo lc_draw_input_field('title', null, 'id="editTitle" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="content" class="label"><?php echo $lC_Language->get('field_content'); ?></label>'+
                   '        <?php echo lc_draw_textarea_field('content', null, 60, 20, 'id="editContent" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_newsletter'); ?>',
          width: 600,
          scrolling: false,
          actions: {
            'Close' : {
              color: 'red',
              click: function(win) { win.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_close'); ?>': {
              classes:  'glossy',
              click:    function(win) { win.closeModal(); }
            },
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#updateEntry").validate({
                rules: {
                  title: { required: true },
                  content: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#updateEntry").serialize(); 
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&nid=NID&BATCH'); ?>'  
                  $.getJSON(jsonLink.replace('NID', id).replace('BATCH', nvp),          
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
            }
          },
          buttonsLowPadding: true
      });
      $("#editModule").html("");  // clear the old values
      $.each(data.modulesArray, function(val, text) {
        var selected = (data.module == val) ? 'selected="selected"' : '';
        if(data.module == val) {
          $("#editModule").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#editModule").append(
          $('<option ' + selected + '></option>').val(val).html(text)
        );
      });
      $("#editTitle").val(data.title);
      $("#editContent").val(data.content);      
    }              
  ); 
}
</script>