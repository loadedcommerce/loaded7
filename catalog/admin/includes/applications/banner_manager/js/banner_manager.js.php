<?php
/*
  $Id: banner_manager.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template;
?>
<script>
$(document).ready(function() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>';
  var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>'; 
     
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType, 
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                  { "sWidth": "40%", "bSortable": true, "sClass": "dataColBanners" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColGroup hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColStats hide-on-tablet" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
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
  
  if (quickAdd) {
    newBanner();
  }     
});
</script>