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
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getInfoData&module=MODULE'); ?>';
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
          content: '<div id="showInfo">'+
                   '  <div id="showInfoForm">'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="title" class="label"><?php echo $lC_Language->get('field_title'); ?></label>'+
                   '      <span id="title"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="description" class="label"><?php echo $lC_Language->get('field_description'); ?></label>'+
                   '      <span id="description"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="author" class="label"><?php echo $lC_Language->get('field_author'); ?></label>'+
                   '      <span id="author"></span>'+
                   '    </p>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_view_module_info'); ?>',
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
      $("#infoContent").attr("title", data.title);
      $("#title").html(data.title);
      $("#description").html(data.description);
      $("#author").html(data.author);
      if ($.template.mediaQuery.isSmallerThan('desktop')) {
        $('.modal').attr('style', 'top:100px !important; left: 25%;  margin-left: -50px;');  
      } 
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('.modal').attr('style', 'top:30px !important; left: 19%;  margin-left: -50px;');  
      }       
    }
  );
}
</script>