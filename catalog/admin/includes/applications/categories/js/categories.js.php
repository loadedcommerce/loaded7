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
global $lC_Template, $lC_Language, $lC_ObjectInfo;
?>
<script>  
function _refreshDataTable() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getAll&media=MEDIA'); ?>';
  
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "bDestroy": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType,  
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aaSorting": [[5,'asc']],
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                  { "sWidth": "30%", "bSortable": true, "sClass": "dataColCategory" },
                  { "sWidth": "7%", "bSortable": true, "sClass": "dataColStatus hide-on-mobile", "sType": "string" },
                  { "sWidth": "9%", "bSortable": false, "sClass": "dataColVisibility hide-on-mobile" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColMode hide-on-mobile" },
                  { "sWidth": "9%", "bSortable": true, "sClass": "dataColSort hide-on-mobile" },
                  { "sWidth": "25%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
}   
  
$(document).ready(function() {
  _refreshDataTable();
  $('.sorted_table').sortable({  
    containerSelector: 'tbody',
    itemSelector: 'tr',
    placeholder: '<tr class="placeholder" />',
    tolerance: '1',
    onDragStart: function (item, group, _super) {
      item.css({
        height: item.height(),
        width: item.width()
      });
      item.addClass("dragged");
      $('body').addClass('dragging');
    },
    onDrop: function  (item, container, _super) { 
      item.removeClass("dragged");
      $("body").removeClass("dragging");
      var nvp = $('#batch').serialize();
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=cSort&NVP'); ?>';
      $.getJSON(jsonLink.replace('NVP', nvp),
        function (data) {
          _refreshDataTable();
        }
      );
    }    
  }); 
  // instantiate breadcrumb
  $("#breadCrumb0").jBreadCrumb();

  var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>';
  
  <?php if (!$_GET['action']) { ?>
    if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {  
      $('#floating-button-container').hide();
    }
  <?php } ?>
  
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#floating-menu-div-listing').fixFloat();
    $('#actionText').hide();
    $('.on-mobile').show();
    $('.selectContainer').hide();
  } else {
    // instantiate floating menu
    $('#floating-menu-div-listing').fixFloat();
  }
    
  if (quickAdd) {
    newCategory();
  }
  <?php if ($_GET['action'] != '') { ?>
  createUploader();
  var qqbuttonhtmlold = $('.qq-upload-button').html();
  var qqbuttonhtml = qqbuttonhtmlold.replace(/Upload a file/i, 'Upload');
  $('.qq-upload-button').html(qqbuttonhtml);
  $('.qq-upload-list').hide();
  
  <?php
    foreach ( $lC_Language->getAll() as $l ) {  
      echo "CKEDITOR.replace('ckEditorCategoriesDescription_" . $l['id'] . "', { height: 200, width: '99%' });";
    }
  ?>
  <?php } ?>
});
                  
function createUploader() {
  var uploader = new qq.FileUploader({
    element: document.getElementById('fileUploaderImageContainer'),
    action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
    onComplete: function(id, fileName, responseJSON) {
      $('#imagePreviewContainer').html('<img src="../images/categories/' + fileName + '" border="0" style="max-width:100%;" /><input type="hidden" id="categories_image" name="categories_image" value="' + fileName + '">');
    },
  });
}

function doSelectFunction(e) {
  if (e.value == 'delete') {
    batchDelete();
  } else if (e.value == 'move') {
    batchMove();
  }
}

function customCheck() {
  var cModeVal = $("#categories_mode").val();
  if (cModeVal == 'specials' /*|| cModeVal == 'featured' */|| cModeVal == 'new' || cModeVal == 'search' || cModeVal == 'cart' || cModeVal == 'account' || cModeVal == 'info' || cModeVal == 'override') {
    $("#categories_custom_url").show();
    $("#categories_link_target_info").hide();
    $("#categories_link_target_p").hide(); 
    if (cModeVal != 'override') {
      $("#categories_custom_url").attr('readonly', 'readonly');
      $("#custom_url_text").hide();
      if (cModeVal == 'specials') {
        $("#categories_custom_url").val('products.php?specials');
      //} else if (cModeVal == 'featured') {
      //  $("#categories_custom_url").val('');
      } else if (cModeVal == 'new') {
        $("#categories_custom_url").val('products.php?new');
      } else if (cModeVal == 'search') {
        $("#categories_custom_url").val('search.php');
      } else if (cModeVal == 'cart') {
        $("#categories_custom_url").val('checkout.php');
      } else if (cModeVal == 'account') {
        $("#categories_custom_url").val('account.php');
      } else if (cModeVal == 'info') {
        $("#categories_custom_url").val('info.php');
      }
    } else {
      $("#categories_link_target_p").show(); 
      $("#categories_custom_url").removeAttr('readonly');
      $("#categories_custom_url").val('');
      $("#custom_url_text").show();
    }
  } else {
    $("#categories_custom").hide();
    $("#categories_link_target_p").hide();
    $("#categories_link_target_info").show();
    $("#categories_custom_url").val("");
    $("#categories_link_target").removeAttr("checked");
    $("#categories_link_target").parent("span").removeClass("checked");
  }
}

function updateStatus(id, val) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateStatus&cid=CID&val=VAL'); ?>';
  $.getJSON(jsonLink.replace('CID', id).replace('VAL', val));
  if (val == 1) {               
    $("#status_" + id).attr('onclick', 'updateStatus(\'' + id + '\', \'0\')');
    $("#status_" + id).html('<span class="icon-tick icon-size2 icon-green cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_disable_category'); ?>"></span>');
  } else {               
    $("#status_" + id).attr('onclick', 'updateStatus(\'' + id + '\', \'1\')');
    $("#status_" + id).html('<span class="icon-cross icon-size2 icon-red cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_enable_category'); ?>"></span>');
  }
}

function updateVisibilityNav(id, val) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateVisibilityNav&cid=CID&val=VAL'); ?>';
  $.getJSON(jsonLink.replace('CID', id).replace('VAL', val));
  if (val == 1) {               
    $("#nav_" + id).attr('onclick', 'updateVisibilityNav(\'' + id + '\', \'0\')');
    $("#nav_" + id).html('<span class="icon-directions icon-size2 icon-green cursor-pointer with-tooltip"></span>');
  } else {               
    $("#nav_" + id).attr('onclick', 'updateVisibilityNav(\'' + id + '\', \'1\')');
    $("#nav_" + id).html('<span class="icon-directions icon-size2 icon-silver cursor-pointer with-tooltip"></span>');
  }
}

function updateVisibilityBox(id, val) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateVisibilityBox&cid=CID&val=VAL'); ?>';
  $.getJSON(jsonLink.replace('CID', id).replace('VAL', val));
  if (val == 1) {               
    $("#box_" + id).attr('onclick', 'updateVisibilityBox(\'' + id + '\', \'0\')');
    $("#box_" + id).html('<span class="icon-browser icon-size2 icon-green cursor-pointer with-tooltip"></span>');
  } else {               
    $("#box_" + id).attr('onclick', 'updateVisibilityBox(\'' + id + '\', \'1\')');
    $("#box_" + id).html('<span class="icon-browser icon-size2 icon-silver cursor-pointer with-tooltip"></span>');
  }
}

function toggleEditor(id) {
  var selection = $("#ckEditorCategoriesDescription_" + id);
  if ($(selection).is(":visible")) {
    $('#ckEditorCategoriesDescription_' + id).hide();
    $('#cke_ckEditorCategoriesDescription_' + id).show();
  } else {
    $('#ckEditorCategoriesDescription_' + id).attr('style', 'width:99%');
    $('#cke_ckEditorCategoriesDescription_' + id).hide();
  }
}
</script>