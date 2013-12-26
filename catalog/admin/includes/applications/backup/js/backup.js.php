<?php
/*
  $Id: backup.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language;
?>
<script>
$(document).ready(function() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>';   
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType, 
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataRowCheck hide-on-mobile" },
                  { "sWidth": "40%", "bSortable": true, "sClass": "dataRowBackups" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataRowDate hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataRowSize hide-on-mobile" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataRowAction" }]
  });
  $('#dataTable').responsiveTable();
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
    $('.selectContainer').hide();
  }
  var error = '<?php echo $_SESSION['error']; ?>';
  if (error) {
    var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
    $.modal.alert(errmsg);
  }
  var restoreLocal = '<?php echo $_SESSION['restoreLocal']; ?>';
  if (restoreLocal == true) {
    setTimeout(function() {
      $.modal.alert('<?php echo $lC_Language->get('message_backup_success'); ?>');
    }, 1000);
  <?php
    $_SESSION['restoreLocal'] = false;
  ?>
  }
});
  
function doForget() {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=doForget'); ?>';   
  $.getJSON(jsonLink,
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";     
        $(location).attr('href',url);
      }   
      if (data.rpcStatus == 1) {
        $('#lastRestoreDate').html('');
      }       
    }              
  );
}  
</script>