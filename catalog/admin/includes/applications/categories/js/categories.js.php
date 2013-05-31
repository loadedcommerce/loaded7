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
function _refreshDataTable() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getAll&media=MEDIA'); ?>';
     
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "bDestroy": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType,  
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "aaSorting": [[4,'asc']],
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                  { "sWidth": "45%", "bSortable": true, "sClass": "dataColCategory" },
                  { "sWidth": "6%", "bSortable": true, "sClass": "dataColShow hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColType hide-on-mobile-portrait" },
                  { "sWidth": "9%", "bSortable": true, "sClass": "dataColSort hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
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
  
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
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
    
  if (quickAdd) {
    newCategory();
  }
  
  $(".clEditorCategoriesDescription").cleditor({width:"99%", height:"255"});
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
    $("#categories_link_target_p").show();
    $("#categories_link_target_info").hide();
  } else {
    $("#categories_custom").hide();
    $("#categories_link_target_p").hide();
    $("#categories_link_target_info").show();
    $("#categories_custom_url").val("");
    $("#categories_link_target").removeAttr("checked");
    $("#categories_link_target").parent("span").removeClass("checked");
  }
}

function toggleEditor(id) {
  var editorHidden = $(".clEditorCategoriesDescription").is(":visible");
  if (editorHidden) {
    //alert('show');
    $(".clEditorCategoriesDescription").cleditor({width:"99%", height:"255"});
  } else {
    //alert('hide');
    var editor = $(".clEditorCategoriesDescription").cleditor()[0];
    editor.$area.insertBefore(editor.$main); // Move the textarea out of the main div
    editor.$area.removeData("cleditor"); // Remove the cleditor pointer from the textarea
    editor.$main.remove(); // Remove the main div and all children from the DOM
    $(".clEditorCategoriesDescription").show();
  }
}
</script>