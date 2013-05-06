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
#newBanner { padding-bottom:20px; }
#bannerNotes li { margin-right:10px; } 
</style>
<script>
function newBanner() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData'); ?>';  
  $.getJSON(jsonLink, 
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
          content: '<div id="newBanner">'+
                   '  <div id="newBannerForm">'+
                   '    <form name="bNew" id="bNew" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=save'); ?>" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_banner'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '        <?php echo lc_draw_input_field('title', null, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="url" class="label"><?php echo $lC_Language->get('field_url'); ?></label>'+
                   '        <?php echo lc_draw_input_field('url', null, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="group" class="label"><?php echo $lC_Language->get('field_group'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('group', null, null, 'class="select" style="width:46%;"') . $lC_Language->get('field_group_new') . '<br />' . lc_draw_input_field('group_new', null, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="image" class="label"><?php echo $lC_Language->get('field_image'); ?></label>'+
                   '        <?php echo lc_draw_file_field('image', true, 'class="file"') . ' ' . $lC_Language->get('field_image_local') . '<br />' . realpath('../images/') . '/' . lc_draw_input_field('image_local', null, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="address_format" class="label"><?php echo $lC_Language->get('field_image_target'); ?></label>'+
                   '        <?php echo realpath('../images') . '/' . lc_draw_input_field('image_target', null, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="html_text" class="label"><?php echo $lC_Language->get('field_html_text'); ?></label>'+
                   '        <?php echo lc_draw_textarea_field('html_text', null, 60, 5, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_scheduled" class="label"><?php echo $lC_Language->get('field_scheduled_date'); ?></label>'+
                   '          <span class="input">'+
                   '            <span class="icon-calendar"></span>'+
                   '            <?php echo lc_draw_input_field('date_scheduled', null, 'class="input-unstyled datepicker"'); ?>'+
                   '          </span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_expires" class="label"><?php echo $lC_Language->get('field_expiry_date'); ?></label>'+
                   '          <span class="input">'+
                   '            <span class="icon-calendar"></span>'+
                   '            <?php echo lc_draw_input_field('date_expires', null, 'class="input-unstyled datepicker"'); ?>'+
                   '          </span>'+
                   '      </p>'+  
                   '      <p class="button-height inline-label">'+
                   '        <label for="expires_impressions" class="label"><?php echo $lC_Language->get('field_maximum_impressions'); ?></label>'+
                   '        <?php echo lc_draw_input_field('expires_impressions', null, 'class="input" maxlength="7" size="7"'); ?>'+
                   '      </p>'+                       
                   '      <p class="button-height inline-label">'+
                   '        <label for="status" class="label"><?php echo $lC_Language->get('field_status'); ?></label>'+
                   '         <?php echo '&nbsp;' . lc_draw_checkbox_field('status', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
                   '      </p>'+
                   '    </form>'+
                   '    <hr>'+
                   '    <div id="bannerNotes"><?php echo $lC_Language->get('info_banner_fields'); ?></div>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_banner'); ?>',
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
                var hasImg = ($("#image").val() == '') ? false : true;
                var hasLocalImg = ($("#image_local").val() == '') ? false : true;
                var hasHtmlText = ($("#html_text").val() == '') ? false : true;
                if (!hasImg && !hasLocalImg && !hasHtmlText) {
                  $.modal.alert('<?php echo $lC_Language->get('ms_error_no_image_or_text'); ?>');                
                  return false;
                }
                var bValid = $("#bNew").validate({
                rules: {
                  title: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  $("#bNew").submit();
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $('.datepicker').glDatePicker({ zIndex: 1000 });    
      $("#group").html("");  // clear the old values
      i=0;
      $.each(data.groupsArray, function(val, text) {
         if(i == 0) {
          $("#group").closest("span + *").prevAll("span.select-value:first").text(text);
          i++;
        }
        $("#group").append(
          $("<option></option>").val(val).html(text)
        );
      });      
    }              
  ); 
}
</script>