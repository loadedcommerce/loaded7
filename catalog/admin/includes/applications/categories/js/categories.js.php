<?php
/*
  $Id: categories.js.php v1.0 2013-01-01 datazen $

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
  // instantiate breadcrumb
  $("#breadCrumb0").jBreadCrumb();

  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getAll&media=MEDIA'); ?>';
  var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>';
     
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType,  
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aaSorting": [[2,'asc']],
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                  { "sWidth": "60%", "bSortable": true, "sClass": "dataColCategory" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColSort hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#floating-button-container').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
    $('.selectContainer').hide();
  } else {
    // instantiate floating menu
    $('#floating-menu-div-listing').fixFloat();
  }     
  var error = '<?php echo $_SESSION['error']; ?>';
  if (error) {
    var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
    $.modal.alert(errmsg);
  } 
  
  if (quickAdd) {
    newCategory();
  }
});

function doSelectFunction(e) {
  if (e.value == 'delete') {
    batchDelete();
  } else if (e.value == 'move') {
    batchMove();
  }
}

function customCheck() {
  var cModeVal = $("#categories_mode").val();
  if (cModeVal == 'override') {
    $("#categories_custom").show();
  } else {
    $("#categories_custom").hide();
    $("#categories_custom_url").val("");
  }
}
</script>