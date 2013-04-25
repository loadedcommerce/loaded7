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
#editBanner { padding-bottom:20px; }
#bannerNotes li { margin-right:10px; }  
</style>
<script>
function editBanner(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&bid=BID'); ?>';  
  $.getJSON(jsonLink.replace('BID', id), 
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
          content: '<div id="editBanner">'+
                   '  <div id="editBannerForm">'+
                   '    <form name="bEdit" id="bEdit" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=save'); ?>" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_banner'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '        <?php echo lc_draw_input_field('title', null, 'id="editTitle" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="url" class="label"><?php echo $lC_Language->get('field_url'); ?></label>'+
                   '        <?php echo lc_draw_input_field('url', null, 'id="editUrl" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="group" class="label"><?php echo $lC_Language->get('field_group'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('group', null, null, 'id="editGroup" class="input with-small-padding"') . $lC_Language->get('field_group_new') . '<br />' . lc_draw_input_field('group_new', null, 'class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="image" class="label"><?php echo $lC_Language->get('field_image'); ?></label>'+
                   '        <?php echo lc_draw_file_field('image', true, 'id="editImage" class="file"') . ' ' . $lC_Language->get('field_image_local') . '<br />' . realpath('../images/') . '/' . lc_draw_input_field('image_local', null, 'id="editImageLocal" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="address_format" class="label"><?php echo $lC_Language->get('field_image_target'); ?></label>'+
                   '        <?php echo realpath('../images') . '/' . lc_draw_input_field('image_target', null, 'id="editImageTarget" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="html_text" class="label"><?php echo $lC_Language->get('field_html_text'); ?></label>'+
                   '        <?php echo lc_draw_textarea_field('html_text', null, 60, 5, 'id="editHtmlText" class="input" style="width:93%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_scheduled" class="label"><?php echo $lC_Language->get('field_scheduled_date'); ?></label>'+
                   '          <span class="input">'+
                   '            <span class="icon-calendar"></span>'+
                   '            <?php echo lc_draw_input_field('date_scheduled', null, 'id="editDateScheduled" class="input-unstyled datepicker"'); ?>'+
                   '          </span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_expires" class="label"><?php echo $lC_Language->get('field_expiry_date'); ?></label>'+
                   '          <span class="input">'+
                   '            <span class="icon-calendar"></span>'+
                   '            <?php echo lc_draw_input_field('date_expires', null, 'id="editDateExpires" class="input-unstyled datepicker"'); ?>'+
                   '          </span>'+
                   '      </p>'+  
                   '      <p class="button-height inline-label">'+
                   '        <label for="expires_impressions" class="label"><?php echo $lC_Language->get('field_maximum_impressions'); ?></label>'+
                   '        <?php echo lc_draw_input_field('expires_impressions', null, 'id="editExpiresImpressions" class="input" maxlength="7" size="7"'); ?>'+
                   '      </p>'+                       
                   '      <p class="button-height inline-label">'+
                   '        <label for="status" class="label"><?php echo $lC_Language->get('field_status'); ?></label>'+
                   '         <?php echo '&nbsp;' . lc_draw_checkbox_field('status', null, null, 'id="editStatus" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
                   '      </p>'+
                   '    </form>'+
                   '    <hr>'+
                   '    <div id="bannerNotes"><?php echo $lC_Language->get('info_banner_fields'); ?></div>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_banner'); ?>',
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
                var hasImg = ($("#editImage").val() == '') ? false : true;
                var hasLocalImg = ($("#editImageLocal").val() == '') ? false : true;
                var hasHtmlText = ($("#editHtmlText").val() == '') ? false : true;
                if (!hasImg && !hasLocalImg && !hasHtmlText) {
                  $.modal.alert('<?php echo $lC_Language->get('ms_error_no_image_or_text'); ?>');                
                  return false;
                }
                var bValid = $("#bEdit").validate({
                rules: {
                  title: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&bid=BID&action=save'); ?>';
                  var actionUrl = url.replace('BID', id);
                  $("#bEdit").attr("action", actionUrl);
                  $("#bEdit").submit();                
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $('.datepicker').glDatePicker({ zIndex: 1000 }); 
         
      $("#editGroup").html("");  // clear the old values
      $.each(data.groupsArray, function(val, text) {
        var selected = (data.bannerData.banners_group == val) ? 'selected="selected"' : '';
        $("#editGroup").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("#editTitle").val(data.bannerData.banners_title);
      $("#editUrl").val(data.bannerData.banners_url);
      $("#editImageLocal").val(data.bannerData.banners_image);
      $("#editGroup").val(data.bannerData.banners_group);
      $("#editHtmlText").val(data.bannerData.banners_html_text);
      $("#editExpiresImpressions").val(data.bannerData.expires_impressions);
      if (data.bannerData.expires_date) $("#editDateExpires").val(data.bannerData.expires_date.substr(0,10));
      if (data.bannerData.date_scheduled) $("#editDateScheduled").val(data.bannerData.date_scheduled.substr(0,10));
      if (data.bannerData.status == 1) { 
        $("#editStatus").attr('checked', true);
      } else {
        $("#editStatus").attr('checked', false);
      } 
           
    }              
  ); 
}
</script>