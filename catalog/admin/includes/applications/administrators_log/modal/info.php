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
function showAdminLogInfo(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAdminLogData&lid=LID'); ?>'  
  $.getJSON(jsonLink.replace('LID', parseInt(id)),
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
          content: '<div id="infoContent"><style>TD { padding:5px; }</style>'+
                   '  <p id="infoContentMessage"></p>'+
                   '  <table id="infoTable" border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">'+
                   '    <thead>'+
                   '      <tr>'+
                   '        <th align="left"><?php echo $lC_Language->get('table_heading_fields'); ?></th>'+
                   '        <th align="left"><?php echo $lC_Language->get('table_heading_value_old'); ?></th>'+
                   '        <th align="left"><?php echo $lC_Language->get('table_heading_value_new'); ?></th>'+
                   '      </tr>'+
                   '    </thead>'+
                   '    <tbody class="with-padding"></tbody>'+
                   '  </table>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_log_entry_info'); ?>'+
                  '<div id="infoLegend" style="position:absolute; top:13px; right:15px">'+
                  '  <span><?php echo $lC_Language->get('table_action_legend'); ?>&nbsp;</span>'+
                  '  <span style="background-color:#E23832; color:#fff;">&nbsp;<?php echo strtoupper($lC_Language->get('button_delete')); ?>&nbsp;</span>'+
                  '  <span style="background-color:#96E97A; color:#000;">&nbsp;<?php echo strtoupper($lC_Language->get('button_insert')); ?>&nbsp;</span>'+
                  '  <span style="background-color:#FFC881; color:#000;">&nbsp;<?php echo strtoupper($lC_Language->get('button_update')); ?>&nbsp;</span>'+
                  '</div>',
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
            }
          },
          buttonsLowPadding: true
      });
      $("#infoContentMessage").html('<table width="100%" border="0"><tr><td width="16"><span class="icon-question-round"></span></td><td style="font-weight:bold;">' + data.user_name + ' > ' + data.module_action + ' > ' + data.module + ' > '  + data.module_id + '</td><td align="right><b><?php echo  $lC_Language->get("field_date"); ?>&nbsp;' + '</b> ' + data.date + '</td></tr></table>');
      $("#infoTable > tbody").html(data.log_html);
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