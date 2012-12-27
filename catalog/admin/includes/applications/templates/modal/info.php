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
<script>
function showInfo(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
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
          content: '<div id="showInfo">'+
                   '  <div id="showInfoForm">'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '      <span id="infoContentTitle"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="title" class="label"><?php echo $lC_Language->get('field_author'); ?></label>'+
                   '      <span id="infoContentAuthor"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="markup" class="label"><?php echo $lC_Language->get('field_markup'); ?></label>'+
                   '      <span id="infoContentMarkup"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="css_based" class="label"><?php echo $lC_Language->get('field_css_based'); ?></label>'+
                   '      <span id="infoContentCssBased"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="presentation_medium" class="label"><?php echo $lC_Language->get('field_presentation_medium'); ?></label>'+
                   '      <span id="infoContentMedium"></span>'+
                   '    </p>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_view_template_info'); ?>',
          width: 500,
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
            }
          },
          buttonsLowPadding: true
      });
      $("#infoContentTitle").html(data.title);
      $("#infoContentAuthor").html(data.author);
      $("#infoContentMarkup").html(data.markup);
      $("#infoContentCssBased").html(data.css_based);
      $("#infoContentMedium").html(data.medium);
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('.modal').attr('style', 'top:10px !important; left: 19%;  margin-left: -50px;');  
      }      
    }
  );
}
</script>