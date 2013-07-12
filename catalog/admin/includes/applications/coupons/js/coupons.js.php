<?php
/*
  $Id: coupons.js.php v1.0 2013-01-01 datazen $

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
  var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>';
    
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType, 
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColName" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColCode hide-on-tablet" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColReward hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColLimits hide-on-mobile" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColRestrictions hide-on-mobile" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
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
    //newCoupon();
  }  
  
  <?php 
    if ($_GET['action'] != '') {
      foreach ( $lC_Language->getAll() as $l ) {  
        echo "CKEDITOR.replace('ckEditorCouponsDescription_" . $l['id'] . "', { height: 200, width: '99%' });";
      }
    } 
  ?>
  
  //$('.datepicker').glDatePicker({ zIndex: 100 });
  
});

function toggleEditor(id) {
  var selection = $("#ckEditorCouponsDescription_" + id);
  if ($(selection).is(":visible")) {
    $('#ckEditorCouponsDescription_' + id).hide();
    $('#cke_ckEditorCouponsDescription_' + id).show();
  } else {
    $('#ckEditorCouponsDescription_' + id).attr('style', 'width:99%');
    $('#cke_ckEditorCouponsDescription_' + id).hide();
  }
}

function validateForm(e) {
  // turn off messages
  jQuery.validator.messages.required = "";

  var bValid = $("#coupon").validate({
    invalidHandler: function() {
    },
    rules: {
      <?php
      foreach ( $lC_Language->getAll() as $l ) {
        ?>
        'coupons_name[<?php echo $l['id']; ?>]': {
          required: true,
        },
        <?php
      }
      ?>
    },
    
    messages: {
      <?php
      foreach ( $lC_Language->getAll() as $l ) {
        ?>
        //"products_keyword[<?php //echo $l['id']; ?>]": "<?php //echo $lC_Language->get('ms_error_product_keyword_exists'); ?>",
        <?php
      }
      ?>
    } 
  }).form();
  $("#languageTabs").refreshTabs();
  if (bValid) {
    $(e).submit();
  } 

  return false;
}
</script>