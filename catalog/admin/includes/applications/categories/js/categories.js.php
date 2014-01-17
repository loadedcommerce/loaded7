<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: categories.js.php v1.0 2013-08-08 datazen $
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
    handle: '.dragsort',
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
  
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
    $('.selectContainer').hide();
  }
  <?php 
  if ($_GET['action'] != '') { 
  ?>
    createUploader();
  <?php
    if (ENABLE_EDITOR == '1') { 
      if (USE_DEFAULT_TEMPLATE_STYLESHEET == "1") {
        foreach ( $lC_Language->getAll() as $l ) {  
          echo "CKEDITOR.replace('ckEditorCategoriesDescription_" . $l['id'] . "', { height: 200, width: '99%', filebrowserUploadUrl: '../ext/jquery/ckeditor/ck_upload.php', extraPlugins: 'stylesheetparser', contentsCss: '../templates/" . $lC_Template->getCode($lC_Template->getID) . "/css/styles.css', stylesSet: [] });";
        }
      } else {
        foreach ( $lC_Language->getAll() as $l ) {  
          echo "CKEDITOR.replace('ckEditorCategoriesDescription_" . $l['id'] . "', { height: 200, width: '99%', filebrowserUploadUrl: '../ext/jquery/ckeditor/ck_upload.php' });";
        }
      }
    } else {
      foreach ( $lC_Language->getAll() as $l ) {  
        echo '$("#ckEditorCategoriesDescription_' . $l['id'] . '").css("height", "200px").css("width", "99.8%");';
      }
    }
  } ?>
  // remove logo image
  $("#imagePreviewContainer").mouseover(function() {
    var cImage = $('#categories_image').val();
    if (cImage != '') {
      $("#clogo_controls").show();
      $("#imagePreviewContainer").css("background", "none repeat scroll 0 0 rgba(0, 0, 0, 0.45)");
      $("#imagePreviewContainer").css("border-radius", "4px 4px 4px 4px");
    }
  })
  .mouseout(function() {
    $("#clogo_controls").hide();
    $("#imagePreviewContainer").css("background", "none");
  });
});
                  
function createUploader() {
  var cImage = $('#categories_image').val();
  var uploader = new qq.FileUploader({
    element: document.getElementById('fileUploaderImageContainer'),
    action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=fileUpload'); ?>',
    onComplete: function(id, fileName, responseJSON) {
      $('#imagePreviewContainer').html('<div style="position:relative;"><div id="clogo_controls" class="controls"><span class="button-group compact children-tooltip"><a onclick="deleteCatImage(<?php echo $_GET[$lC_Template->getModule()]; ?>);" class="button icon-trash" href="#" title="<?php echo $lC_Language->get('text_delete'); ?>"></a></span></div></div><img src="../images/categories/' + fileName + '" border="0" style="max-width:100%;" /><input type="hidden" id="categories_image" name="categories_image" value="' + fileName + '">');
    },
  });
}

function deleteCatImage(id) {
  var cImage = $('#categories_image').val();
  $("#clogo_controls").hide();
  $("#imagePreviewContainer").css("background", "none");
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteCatImage&image=IMAGE&id=ID'); ?>';
  $.getJSON(jsonLink.replace('IMAGE', cImage).replace('ID', id));
  $("#imagePreviewContainer").html('<img src="../images/no_image.png" /><input type="hidden" id="categories_image" name="categories_image" value="">');
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
  if (cModeVal == 'specials' || 
      cModeVal == 'featured' || 
      cModeVal == 'new' || 
      cModeVal == 'search' || 
      cModeVal == 'cart' || 
      cModeVal == 'account' || 
      cModeVal == 'info' || 
      cModeVal == 'override') {
    $("#categories_custom").show();
    $("#categories_custom_url").show();
    $("#categories_link_target_info").hide();
    $("#categories_link_target_p").hide(); 
    if (cModeVal != 'override') {
      $("#categories_custom_url").attr('readonly', 'readonly');
      $("#custom_url_text").hide();
      if (cModeVal == 'specials') {
        $("#categories_custom_url").val('products.php?specials');
      } else if (cModeVal == 'featured') {
        $("#categories_custom_url").val('products.php?featured_products');
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
    <?php
    foreach ( $lC_Language->getAll() as $l ) {
      echo '$("#categories_permalink_' . $l['id'] . '").attr("value", "no-permalink").parent().hide();';
    }
    ?>
  } else {
    $("#categories_custom").hide();
    $("#categories_link_target_p").hide();
    $("#categories_link_target_info").show();
    $("#categories_custom_url").val("");
    $("#categories_link_target").removeAttr("checked");
    $("#categories_link_target").parent("span").removeClass("checked");
    <?php
    foreach ( $lC_Language->getAll() as $l ) {
      echo '$("#categories_permalink_' . $l['id'] . '").parent().show();';
    }
    ?>
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
<?php if ($_GET['action'] != '') { ?>

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

function validateForm(e) {
  // turn off messages
  jQuery.validator.messages.required = "";

  var bValid = $("#category").validate({
    invalidHandler: function() {
    },
    rules: {
      <?php
      foreach ( $lC_Language->getAll() as $l ) {
        ?>
        'categories_name[<?php echo $l['id']; ?>]': {
          required: true,
        },
        'categories_permalink[<?php echo $l['id']; ?>]': {
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
        "categories_name[<?php echo $l['id']; ?>]": "<?php echo $lC_Language->get('ms_error_categories_name_required'); ?>",
        "categories_permalink[<?php echo $l['id']; ?>]": "<?php echo $lC_Language->get('ms_error_categories_permalink_required'); ?>",
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
    
function validatePermalink(pl) {
  // turn off messages
  jQuery.validator.messages.required = "";

  var cid = '<?php echo $_GET[$lC_Template->getModule()]; ?>';
  var jsonVKUrl = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=validatePermalink&cid=CID&type=1'); ?>';
  var bValid = $("#category").validate({
    invalidHandler: function() {
    },
    rules: {
      <?php
      foreach ( $lC_Language->getAll() as $l ) {
        ?>
        'categories_permalink[<?php echo $l['id']; ?>]': {
          remote: jsonVKUrl.replace('CID', cid),
        },
        <?php
      }
      ?>
    },    
    messages: {
      <?php
      foreach ( $lC_Language->getAll() as $l ) {
        ?>
        "categories_permalink[<?php echo $l['id']; ?>]": "<?php echo $lC_Language->get('ms_error_categories_permalink_exists'); ?>",
        <?php
      }
      ?>
    } 
  }).form();
  $("#languageTabs").refreshTabs();

  return false;
}

<?php 
  foreach ( $lC_Language->getAll() as $l ) { 
?>
$("#categories_name_<?php echo $l['id']; ?>").blur(function(){
  $("#categories_permalink_<?php echo $l['id']; ?>").val($("#categories_name_<?php echo $l['id']; ?>").val().toLowerCase().replace(/ /g, '-').replace(/[^a-z0-9-]/g, ''));
});
<?php 
  }
}
?>           
</script>
