<?php
/**
  $Id: options_subproducts.inc.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Language, $pInfo; 
?>
<div id="multiTypeControlButtons" class="button-group small-margin-top">
  <label for="type_radio_1" class="button green-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_children') == 1 && $pInfo->getInt('is_subproduct') != 1) ? ' active' : ''); ?>">
    <input type="radio" onclick="toggleMultiSkuTypeRadioGroup(this.value);" name="multi_sku_type_radio_group" id="type_radio_1" value="1" />
    <?php echo $lC_Language->get('text_combo_options'); ?>
  </label>
  <label for="type_radio_2" class="button green-active<?php echo (isset($pInfo) && ($pInfo->getInt('has_subproducts') == 1) ? ' active' : ''); ?>">
    <input type="radio" onclick="toggleMultiSkuTypeRadioGroup(this.value);" name="multi_sku_type_radio_group" id="type_radio_2" value="2" />
    <?php echo $lC_Language->get('text_sub_products'); ?>
  </label>
</div>  
         
<div id="comboOptionsContainer" class="">
  Coming Soon ...
</div>       

<div id="subProductsContainer" class="margin-top">    
  <table width="100%" style="" id="subProductsTable" class="simple-table">
    <thead>
      <tr>
        <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_sub_products_name'); ?></th>
        <th scope="col" class="align-center hide-below-480"><?php echo $lC_Language->get('table_heading_sub_products_status'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sub_products_weight'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sub_products_sku'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sub_products_qoh'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sub_products_cost'); ?></th>
        <th scope="col" class="align-left hide-below-480"><?php echo $lC_Language->get('table_heading_sub_products_img'); ?></th>
        <th scope="col" class="align-right" width="50px"><?php echo $lC_Language->get('table_heading_action'); ?></th>
      </tr>
    </thead>      
    <tbody class="">
    </tbody>
  </table>          
</div>       
<script>
$(document).ready(function() {
  var edit = '<?php echo (isset($pInfo)) ? '1' : '0'; ?>';
  var has_subproducts = '<?php echo (isset($pInfo) && $pInfo->get('has_subproducts') == '1') ? '1' : '0'; ?>';
  var has_variants = '<?php echo (isset($pInfo) && $pInfo->get('has_children') == '1') ? '1' : '0'; ?>';
  var optionsDiv = $("input:radio[name=multi_sku_type_radio_group]").val();
  toggleMultiSkuTypeRadioGroup(optionsDiv);
  
  if (edit == '1') {
    if (has_subproducts == '1') {
      getSubProductsRows();
      _updateInvControlType('2');
      $('#type_radio_2').click();
      $('label[for=\'ic_radio_1\']').addClass('disabled');
      $('label[for=\'ioc_radio_1\']').addClass('disabled');
      $('label[for=\'type_radio_1\']').addClass('disabled');
      $('#type_radio_1').removeAttr('onclick');
    }
  }
  addSubProductsRow(false, false, false);  
  $("#subProductsTable tr:last-child td:first-child").find('input').focus();  

}); 

function getSubProductsRows() {
  var subproducts = <?php echo (isset($pInfo)) ? json_encode($pInfo->get('subproducts')) : json_encode(array()); ?>;
  var editLink = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'products=PID&subproduct=1&cID=' . $_GET['cID'] . '&action=save'); ?>'; 
  var output = '';
  $.each(subproducts, function(key, val) {
    output += '<tr id="tr-' + key + '">'+
              '  <td><input type="text" class="input" onblur="addSubProductsRow(true, this, \'' + key + '\');" onfocus="this.select();" tabindex="' + key + '1" id="sub_products_name_' + key + '" name="sub_products_name[' + key + ']" value="' + val.products_name + '"></td>'+
              '  <td class="align-center align-middle"><input type="hidden" name="sub_products_id[' + key + ']"  value="' + val.products_id + '">'+
              '    <a onclick="setSubProductDefault(\'' + key + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_set_as_default'); ?>" href="javascript:void(0);"><span id="sub_products_default_span_' + key + '" class="icon-star icon-size2 margin-right ' + ((val.is_subproduct == 2) ? "icon-orange" : "icon-grey") + '"></span></a>'+
              '    <a onclick="setSubProductStatus(\'' + key + '\');" class="with-tooltip" id="sub_products_status_link[' + key + ']" title="<?php echo $lC_Language->get('text_sub_products_enable_disable'); ?>" href="javascript:void(0);"><span id="sub_products_status_span_' + key + '" class="icon-tick icon-size2 icon-green"></span></a>'+
              '    <input type="hidden" id="sub_products_default_' + key + '" name="sub_products_default[' + key + ']" class="sub_products_default" value="' + ((val.is_subproduct == 2) ? "1" : "0") + '">'+
              '    <input type="hidden" id="sub_products_status_' + key + '" name="sub_products_status[' + key + ']" value="1">'+
              '  </td>'+
              '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + key + '2" name="sub_products_weight[' + key + ']" value="' + val.products_weight + '"></td>'+
              '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + key + '3" name="sub_products_sku[' + key + ']" value="' + val.products_sku + '"></td>'+
              '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + key + '4" name="sub_products_qoh[' + key + ']" value="' + val.products_quantity + '"></td>'+
              '  <td style="white-space:nowrap;"><div class="inputs" style="display:inline; padding:8px 0;"><span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span><input type="text" class="input-unstyled" style="width:87%;" onfocus="this.select();" tabindex="' + key + '5" name="sub_products_cost[' + key + ']" value="' + val.products_cost + '"></div></td>'+
              '  <td class="align-center align-middle">'+
              '    <input style="display:none;" type="file" id="sub_products_image_' + key + '" name="sub_products_image[' + key + ']" onchange="setSubProductImage(\'' + key + '\');" multiple />'+
              '    <span class="icon-camera icon-size2 cursor-pointer with-tooltip ' + ((val.image != '') ? 'icon-green' : 'icon-grey') + '" title="' + ((val.image != '') ? val.image : null) + '" id="fileSelectButton-' + key + '" onclick="document.getElementById(\'sub_products_image_' + key + '\').click();"></span>'+
              '  </td>'+
              '  <td class="align-right align-middle">'+
        //      '    <a href="' + editLink.replace('PID', val.products_id) + '" class="with-tooltip margin-right" title="<?php echo $lC_Language->get('text_sub_products_edit'); ?>"><span class="icon-pencil icon-size2 icon-blue"></span></a>'+
              '    <a onclick="removeSubProductRow(\'' + key + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_remove'); ?>" href="javascript:void(0)"><span class="icon-cross icon-size2 icon-red"></span></a>'+
              '  </td>'+
              '</tr>';
  });            
  $('#subProductsTable> tbody').append(output);
}

function addSubProductsRow(include_price_row, e, key) {
  if($("#subProductsTable tbody").children().length > 0) {
    var id = $('#subProductsTable tr:last').attr('id').replace('tr-', '');
    $('label[for=\'ic_radio_1\']').addClass('disabled');
    $('label[for=\'ioc_radio_1\']').addClass('disabled');
    $('label[for=\'type_radio_1\']').addClass('disabled');
  } else {
    var id = 0;
    $('label[for=\'ic_radio_1\']').removeClass('disabled');
    $('label[for=\'ioc_radio_1\']').removeClass('disabled');
    $('label[for=\'type_radio_1\']').removeClass('disabled'); 
  }
  if (e.value != undefined) $('#name-td-' + key).text(e.value);                
  if($('#sub_products_name_' + id).val() == '') return false;

  var prevName = $('#sub_products_name_' + id).val();
  var nextId = parseInt(id) + 1;
  var row = '<tr id="tr-' + nextId + '">'+
            '  <td><input type="text" class="input" onblur="addSubProductsRow(true, this, \'' + key + '\');" onfocus="this.select();" tabindex="' + nextId + '1" id="sub_products_name_' + nextId + '" name="sub_products_name[' + nextId + ']" value=""></td>'+
            '  <td class="align-center align-middle">'+
            '    <a onclick="setSubProductDefault(\'' + nextId + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_set_as_default'); ?>" href="javascript:void(0);"><span id="sub_products_default_span_' + nextId + '" class="icon-star icon-size2 margin-right icon-grey"></span></a>'+
            '    <a onclick="setSubProductStatus(\'' + nextId + '\');" class="with-tooltip" id="sub_products_status_link[' + nextId + ']" title="<?php echo $lC_Language->get('text_sub_products_enable_disable'); ?>" href="javascript:void(0);"><span id="sub_products_status_span_' + nextId + '" class="icon-tick icon-size2 icon-green"></span></a>'+
            '    <input type="hidden" id="sub_products_default_' + nextId + '" name="sub_products_default[' + nextId + ']" class="sub_products_default" value="">'+
            '    <input type="hidden" id="sub_products_status_' + nextId + '" name="sub_products_status[' + nextId + ']" value="1">'+
            '  </td>'+
            '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + nextId + '2" name="sub_products_weight[' + nextId + ']" value=""></td>'+
            '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + nextId + '3" name="sub_products_sku[' + nextId + ']" value=""></td>'+
            '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + nextId + '4" name="sub_products_qoh[' + nextId + ']" value=""></td>'+
            '  <td style="white-space:nowrap;"><div class="inputs" style="display:inline; padding:8px 0;"><span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span><input type="text" class="input-unstyled" style="width:87%;" onfocus="this.select();" tabindex="' + nextId + '5" name="sub_products_cost[' + nextId + ']" value="0.0000"></div></td>'+
            '  <td class="align-center align-middle">'+
            '    <input style="display:none;" type="file" id="sub_products_image_' + nextId + '" name="sub_products_image[' + nextId + ']" onchange="setSubProductImage(\'' + nextId + '\');" multiple />'+
            '    <span class="icon-camera icon-size2 icon-grey cursor-pointer with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_select_image'); ?>" id="fileSelectButton-' + nextId + '" onclick="document.getElementById(\'sub_products_image_' + nextId + '\').click();"></span>'+
            '  </td>'+
            '  <td class="align-right align-middle">'+
            '    <a onclick="removeSubProductRow(\'' + nextId + '\', \'' + id + '\');" class="with-tooltip" title="<?php echo $lC_Language->get('text_sub_products_remove'); ?>" href="javascript:void(0)"><span class="icon-cross icon-size2 icon-red"></span></a>'+
            '  </td>'+
          '</tr>';
          
  $('#subProductsTable > tbody').append(row);
   
  if (include_price_row == '1') {       
    var ok = '<?php echo (defined('ADDONS_SYSTEM_LOADED_7_PRO_STATUS') && ADDONS_SYSTEM_LOADED_7_PRO_STATUS == '1') ? '1' : '0'; ?>';
    var groups = <?php echo json_encode(lC_Customer_groups_Admin::getAll()); ?>;
    $('#no-options-' + val.customers_group_id).remove();
    $.each(groups.entries, function( key, val ) {
      var prow = '<tr class="trp-' + nextId + '">'+
                 '  <td id="name-td-' + nextId + '" class="element">' + prevName + '</td>'+
                 '  <td>'+
                 '    <div class="inputs' + ((val.customers_group_id == '1' || ok == '1') ? '' : ' disabled') + '" style="display:inline; padding:8px 0;">'+
                 '      <span class="mid-margin-left no-margin-right"><?php echo $lC_Currencies->getSymbolLeft(); ?></span>'+
                 '      <input type="text" class="input-unstyled" onfocus="$(this).select()" value="0.0000" id="sub_products_price_' + val.customers_group_id + '_' + id + '" name="sub_products_price[' + val.customers_group_id + '][' + id + ']" ' + ((val.customers_group_id == '1' || ok == '1') ? '' : ' DISABLED') + '>'+
                 '    </div>'+
                 '  </td>'+
                 '</tr>';                    
                 
       $('#tbody-' + val.customers_group_id).append(prow);
    });
  }
}   

function removeSubProductRow(id) {
  $('#tr-' + id).remove();
  $('.trp-' + id).remove();
  if($("#subProductsTable tbody").children().length == 0) addSubProductsRow(false, false, false);
}

function setSubProductDefault(id) {
  $('.icon-star').removeClass('icon-orange').addClass('icon-grey');
  $('#sub_products_default_span_' + id).removeClass('icon-grey').addClass('icon-orange');
  $('.sub_products_default').val('0');
  $('#sub_products_default_' + id).val('1');
} 

function setSubProductStatus(id) {
  var v = $('#sub_products_status_' + id).val();
  if (v == '1') {
    $('#sub_products_status_' + id).val('0');
    $('#sub_products_status_span_' + id).removeClass('icon-green icon-tick').addClass('icon-red icon-cross');
  } else {
    $('#sub_products_status_' + id).val('1');
    $('#sub_products_status_span_' + id).removeClass('icon-red icon-cross').addClass('icon-green icon-tick');
  } 
}  

function setSubProductImage(id) {
  setTimeout(function(){
    var v = ($('#sub_products_image_' + id).val());
    if (v != '') {
      $('#fileSelectButton-' + id).removeClass('icon-grey').addClass('icon-green').prop('title', v);
    } else {                                                              
      var title = '<?php echo $lC_Language->get('text_sub_products_select_image'); ?>';
      $('#fileSelectButton-' + id).removeClass('icon-green').addClass('icon-grey').prop('title', title);
    }
  }, 500);
} 

function toggleMultiSkuTypeRadioGroup(val) {
  if (val == '1') {
    $('#comboOptionsContainer').slideDown();  
    $('#subProductsContainer').slideUp();
  } else {
    $('#comboOptionsContainer').slideUp();  
    $('#subProductsContainer').slideDown();            
  }
  
}  
</script>