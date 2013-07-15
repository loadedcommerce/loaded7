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
                  { "sWidth": "25%", "bSortable": true, "sClass": "dataColName" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColStatus hide-on-mobile", "sType": "string" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColCode hide-on-tablet" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColReward hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColLimits hide-on-mobile" },
                  //{ "sWidth": "%", "bSortable": false, "sClass": "dataColRestrictions hide-on-mobile" }, added back in later phase
                  { "sWidth": "25%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
  
  if (quickAdd) {
    newCoupon();
  }  
  
  <?php 
    if ($_GET['action'] != '') {
      foreach ( $lC_Language->getAll() as $l ) {  
        echo "CKEDITOR.replace('ckEditorCouponsDescription_" . $l['id'] . "', { height: 200, width: '99%' });";
      }
    } 
  ?>
  
  $('.datepicker').glDatePicker({ zIndex: 100 });
  
});

function updateStatus(id, val) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateStatus&cid=CID&val=VAL'); ?>';
  $.getJSON(jsonLink.replace('CID', id).replace('VAL', val));
  if (val == 1) {               
    $("#status_" + id).attr('onclick', 'updateStatus(\'' + id + '\', \'0\')');
    $("#status_" + id).html('<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_disable_coupon'); ?>"></span>');
  } else {               
    $("#status_" + id).attr('onclick', 'updateStatus(\'' + id + '\', \'1\')');
    $("#status_" + id).html('<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_enable_coupon'); ?>"></span>');
  }
}

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