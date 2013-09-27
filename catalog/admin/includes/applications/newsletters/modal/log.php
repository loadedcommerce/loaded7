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
function showLog(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 1) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div id="logContent">'+
               '  <p id="logContentMessage"></p>'+
               '  <style>'+
               '  .dataColEmail { text-align: left; }'+
               '  .dataColSent { text-align: center; }'+  
               '  .dataColDate { text-align: center; }'+  
               '  </style>'+
               '  <div class="logPageContainer" style="width:98%;">'+
               '    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="display" id="newsletterLogDataTable">'+
               '      <thead>'+
               '        <tr>'+
               '          <th align="left"><?php echo $lC_Language->get('table_heading_email_addresses'); ?></th>'+
               '          <th align="center"><?php echo $lC_Language->get('table_heading_sent'); ?></th>'+
               '          <th align="center"><?php echo $lC_Language->get('table_heading_date_sent'); ?></th>'+
               '        </tr>'+
               '      </thead>'+
               '      <tbody></tbody>'+
               '      <tfoot></tfoot>'+
               '    </table>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_newsletters_log'); ?>',
      width: 600,
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
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getLog&nid=NID'); ?>';   
  oTable = $("#newsletterLogDataTable").dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('NID', parseInt(id)),
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bSort": false,
    "bInfo": false,
    "bAutoWidth": false,
    "bDestroy": true,
    "aoColumns": [{ "sWidth": "640px", "sClass": "dataColEmail" },
                  { "sWidth": "100px", "sClass": "dataColSent" },
                  { "sWidth": "200px", "sClass": "dataColDate" }]
  });
}
</script>