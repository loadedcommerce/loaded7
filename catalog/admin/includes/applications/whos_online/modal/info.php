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
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getData&sid=SID'); ?>'  
  $.getJSON(jsonLink.replace('SID', id), 
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
                   '      <label for="title" class="label"><?php echo $lC_Language->get('field_session_id'); ?></label>'+
                   '      <span id="sessionID"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="time_online" class="label"><?php echo $lC_Language->get('field_time_online'); ?></label>'+
                   '      <span id="timeOnline"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="customer_id" class="label"><?php echo $lC_Language->get('field_customer_id'); ?></label>'+
                   '      <span id="customerID"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="customer_name" class="label"><?php echo $lC_Language->get('field_customer_name'); ?></label>'+
                   '      <span id="fullName"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="ip_address" class="label"><?php echo $lC_Language->get('field_ip_address'); ?></label>'+
                   '      <span id="ipAddress"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="entry_time" class="label"><?php echo $lC_Language->get('field_entry_time'); ?></label>'+
                   '      <span id="timeEntry"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="last_click" class="label"><?php echo $lC_Language->get('field_last_click'); ?></label>'+
                   '      <span id="timeLastClick"></span>'+
                   '    </p>'+
                   '    <p class="button-height inline-label">'+
                   '      <label for="last_page_url" class="label"><?php echo $lC_Language->get('field_last_page_url'); ?></label>'+
                   '      <span id="lastPageUrl"></span>'+
                   '    </p>'+                   
                   '  </div>'+
                   '</div>',
          title: '<?php echo str_replace("'", "", $lC_Language->get('heading_title')); ?>',
          width: 500,
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
      $("#sessionID").html(id);
      $("#timeOnline").html(data.timeOnline);
      $("#customerID").html(data.customer_id);
      $("#fullName").html(data.full_name);
      $("#ipAddress").html(data.ipAddress);
      $("#timeEntry").html(data.entryTime);
      $("#timeLastClick").html(data.lastClick);
      $("#lastPageUrl").html(data.last_page_url);
      $("#cartContents").html(data.cartContents);
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('.modal').attr('style', 'top:10px !important; left: 19%;  margin-left: -50px;');  
      }      
    }
  );
}
</script>