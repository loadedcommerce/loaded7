<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: addComboOption.php v1.0 2013-08-08 datazen $
*/
global $lC_Language, $lC_Template, $lC_Currencies, $pInfo;
?>
<style>
#addComboOption { padding-bottom:20px; }
#addComboOptionEntry { padding-bottom:20px; }
#addComboOptionEntry2 { padding-bottom:20px; }
#addComboOptionConfirm { padding-bottom:20px; }
.visual > div { max-width:30px; max-height:30px; }
.visual > p { max-width:30px; max-height:30px; }
.visual > img { max-width:30px; max-height:30px; }
</style>
<script>
function addComboOption(editRow) {   
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var hasVariants = '<?php echo (count(lC_Product_variants_Admin::getVariantGroups()) > 0) ? '1' : '0'; ?>';
  if (hasVariants == 0) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_variant_groups');?>');
    return false;
  }
  mask();  
  
  function getOptionsData(id, callback) {
    mask()
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getSimpleOptionEntryData&dummy=0&group=GROUP'); ?>'
    $.getJSON(jsonLink.replace('GROUP', id),
      function (data) {
        unmask();
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          if (data.rpcStatus == -2) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_no_variant_entries'); ?>');
          } else {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          }
          return false;
        }
        
        var visual = '';
        $.each(data, function(key, val) {
          if (val.id != undefined) {
            visual = visual + val.visual;
          }
        }); 
        data.visual = visual;       

        callback(data); 
      }
    );     
  }
  
  function getGroupsSelectOptions(d, s) {
    var options = '';
    var groupText = $('#group').find(":selected").text();
    var cnt = 1;

    if (s == undefined) {
      $.each(d, function(key, val) {
        if (val.id != undefined && val.title != groupText) {
          if (cnt == 1) {
            options += '<option selected="selected" value="' + val.id + '__' + val.title + '">' + val.title + '</option>';
          } else {
            options += '<option value="' + val.id + '__' + val.title + '">' + val.title + '</option>';
          }                    
          cnt++;
        }
      });    
    } else {

      var v = s.toString();
      $.each(d, function(key, val) {
        if (val.id != undefined) { 
          if (v.indexOf(val.id) != -1) {
            options += '<option selected="selected" value="' + val.id + '__' + val.title + '">' + val.title + '</option>';
          } else {
            options += '<option value="' + val.id + '__' + val.title + '">' + val.title + '</option>';
          }                   
        }   
      });   
    }
    
    return options;
  }
  
  function buildCombo(set, combo, callback) {
    var thisCombo = new Array();
    $.each(set, function(key, value) {
      $.each(combo, function(group, values) {
        $.each(values, function(k, v) {
          thisCombo.push(value + '|' + v);
        });    
      }); 
    }); 
    
    callback(thisCombo);
  }
  
  function comboExists(combo, callback) {
    var result = new Array();
    
    result.exists = 0;
    $(".option-name").each(function(){
      var option = $(this).text();
      if (option == combo) {
        result.exists = 1;
      }
    });
    
    callback(result);
  }
  
  function getOptionsRows(data) {
    
    var tbody = '';
    
    // start building new set using primary (1st) element in the array
    var cnt = 1;
    var setArray = new Array();
    $.each(data.combo, function(group, values) {
      if (cnt == 1) {
        setArray = values;
        delete data.combo[group];
      }
      cnt++;  
    });
    
    // get the options matrix
    buildCombo(setArray, data.combo, function(gdata) {
      
      // create the tbody
      var cnt = 200;
      $.each(gdata, function(key, set) {
              
        if (set != undefined) {
          
          var comboInput = '';
          var comboText = '';
          var row = set.split('|');
          $.each(row, function(key, option) {
            oData = option.split('__');
            comboText += oData[1] + ', ';
            comboInput += '<input type="hidden" id="variants_' + cnt + '_values_' + oData[0] + '"  name="variants[' + cnt + '][values][' + oData[0] + ']" value="' + oData[1] + '">';                        
          });
          var newText = comboText.substr(0, comboText.length-2);
          
          // do not insert new row if combo row already esits
          comboExists(newText, function(cdata) { 
            
            if (cdata.exists != 1) {         
          
              var symbolLeft = '<?php echo $lC_Currencies->getSymbolLeft(); ?>';
              var decimals = '<?php echo DECIMAL_PLACES; ?>';
              var statusIcon = '<span id="variants_status_span_' + cnt + '" class="variants-status icon-cross icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Set Status"></span><input type="hidden" class="variants-status-input" id="variants_status_' + cnt + '" name="variants[' + cnt + '][status]" value="0">';
              var defaultIcon = '<span id="variants_default_combo_span_' + cnt + '" class="default-combo-span icon-star icon-size2 icon-grey with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="<?php echo $lC_Language->get('text_default_selected_combo'); ?>"></span><input class="default-combo" type="hidden" id="variants_default_combo_' + cnt + '" name="variants[' + cnt + '][default_combo]" value="0">';
              
              var weight = (data.use_product_weight == 'on') ? parseFloat(data.products_weight).toFixed(4) : 0.0000;
              var sku = '';
              var quantity = data.set_product_qty;
              var price = (data.use_product_price == 'on') ? parseFloat(data.products_price).toFixed(decimals) : 0.00;
                
              tbody += '<tr id="trmso-' + cnt + '" class="new-option"><input type="hidden" name="variants[' + cnt + '][product_id]" value="0"><input type="hidden" name="variants[' + cnt + '][sort]" class="combo-sort" value="">' + comboInput +
                       '  <td width="16px" class="sort-icon dragsort" style="cursor:move;"><span class="icon-list icon-grey icon-size2"></span></td>' +
                       '  <td class="option-name" width="25%">' + newText + '<span class="icon-light-up icon-orange mid-margin-left with-tooltip cursor-pointer" title="<?php echo $lC_Language->get('text_new_option_set_unsaved'); ?>"></span></td>' +
                       '  <td width="16px" style="cursor:pointer;" onclick="toggleComboOptionsFeatured(\'' + cnt + '\');">' + defaultIcon + '</td>' +                    
                       '  <td style="white-space:nowrap;">' +
                       '     <div class="inputs" style="display:inline; padding:8px 0;">' +
                       '       <input type="text" class="input-unstyled mid-margin-left" style="width:70%;" onfocus="this.select();" value="' + weight + '" tabindex="' + cnt + '2" name="variants[' + cnt + '][weight]">'+
                       '       <span class="mid-margin-right no-margin-left"><?php echo lC_Weight::getCode(SHIPPING_WEIGHT_UNIT); ?></span>'+
                       '     </div>' +
                       '   </td>' +                   
                       '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + cnt + '3" name="variants[' + cnt + '][sku]" value="' + sku + '"></td>' +
                       '  <td><input type="text" class="input half-width" onfocus="this.select();" tabindex="' + cnt + '4" name="variants[' + cnt + '][qoh]" value="' + quantity + '"></td>' +
                       '  <td style="white-space:nowrap;">' +
                       '     <div class="inputs" style="display:inline; padding:8px 0;">' +
                       '       <span class="mid-margin-left no-margin-right">' + symbolLeft + '</span>' +
                       '       <input type="text" class="input-unstyled" style="width:87%;" onchange="$(\'#variants_' + cnt + '_price_1\').val(this.value);" onfocus="this.select();" value="' + price + '" tabindex="' + cnt + '5" name="variants[' + cnt + '][price]" name="variants[' + cnt + '][price]">' +
                       '     </div>' +
                       '   </td>' +
                       '  <td class="align-center align-middle">' +
                       '    <input style="display:none;" type="file" id="multi_sku_image_' + cnt + '" name="variants[' + cnt + '][image]" onchange="setComboOptionsImage(\'' + cnt + '\');" multiple />' +
                       '    <span class="icon-camera icon-grey icon-size2 cursor-pointer with-tooltip" title="" id="fileSelectButtonComboOptions-' + cnt + '" onclick="document.getElementById(\'multi_sku_image_' + cnt + '\').click();"></span>' +
                       '  </td>' +
                       '  <td width="16px" align="center" style="cursor:pointer;" onclick="toggleComboOptionsStatus(\'' + cnt + '\');">' + statusIcon + '</td>' +
                       '  <td width="40px" align="right">' +
                       '      <span class="icon-pencil icon-orange icon-size2 margin-right with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Edit Entry" style="cursor:pointer;" onclick="addMultiSKUOption(\'' + cnt + '\')"></span>' +
                       '      <span class="icon-trash icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"right"}\' title="Remove Entry" style="cursor:pointer;" onclick="removeComboOptionsRow(\'' + cnt + '\');"></span>' +
                       '    </td>' +
                       '</tr>'; 
             
             var defaultGroup = '<?php echo DEFAULT_CUSTOMERS_GROUP_ID; ?>';
             var customerGroups = <?php echo json_encode(lC_Customer_groups_Admin::getAll()); ?>;
             var lastID = 0;
             $.each(customerGroups.entries, function(key, val) {
               var customers_group_id = val.customers_group_id;

               if (lastID != customers_group_id) {
                 shown = false;
                 lastID = customers_group_id;                       
               }
               
               var noOptions = $("#tbody-combo-options-pricing-" + val.customers_group_id).length;
               if (noOptions == 0) {
                 $('#no-options-' + val.customers_group_id).remove();
                 var pTable = '<div class="combo-options-pricing-container">' +
                              '  <div class="big-text underline margin-top" style="padding-bottom:8px;"><?php echo $lC_Language->get('text_combo_options'); ?></div>' +
                              '  <table class="simple-table combo-options-pricing-table">' +
                              '    <tbody id="tbody-combo-options-pricing-' + val.customers_group_id + '"></tbody>' +
                              '  </table>' +
                              '</div>';
                                                  
                 $('#options-pricing-entries-div-' + val.customers_group_id).append(pTable);                         
               }                
               
               var pTbody = '';          
               pTbody += '<tr class="trpmso-' + cnt + ' new-option">' +
                         '  <td id="co-name-td-' + customers_group_id + '-' + cnt + '" class="element">' + newText + '<span class="icon-light-up icon-orange mid-margin-left with-tooltip cursor-pointer" title="<?php echo $lC_Language->get('text_new_option_set_unsaved'); ?>"></span></td>' +
                         '  <td>' +
                         '    <div class="inputs' + ((customers_group_id == defaultGroup) ? '' : ' disabled') + '" style="display:inline; padding:8px 0;">' +
                         '      <span class="mid-margin-left no-margin-right">' + symbolLeft + '</span>' +
                         '      <input type="text" class="input-unstyled" onchange="$(\'#variants_' + cnt + '_price\').val(this.value);" onfocus="$(this).select()" value="' + ((customers_group_id == defaultGroup) ? price : parseFloat(0).toFixed(decimals)) + '" id="variants_' + cnt + '_price_' + customers_group_id + '" name="variants[' + cnt + '][price][' + customers_group_id + ']"' + ((customers_group_id == defaultGroup) ? '' : ' READONLY') + '>' +
                         '    </div>' +
                         '  </td>' +
                         '</tr>'; 
                         
               $('#tbody-combo-options-pricing-' + customers_group_id).append(pTbody);  
             });
            }         
        });                   
                                   
        }
        cnt++;
      });    
      
      $('#comboOptionsTable').append(tbody);
      _resortComboOptions();
      toggleSubproductsButtonDisable(0);
      
    });    
  }
  
  var set = '';
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getSimpleOptionData'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      unmask();
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="addComboOption">'+
                   '  <div id="addComboOptionForm">'+
                   '    <form name="sAdd" id="sAdd" action="" method="post">'+
                   '      <p class="button-height block-label">'+
                   '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_primary_group'); ?></label>'+
                   '        <span id="groupSelectContainer"></span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_combo_option'); ?>',
          width: 320,
                actions: {
            'Close' : {
              color: 'red',
              click: function(win) { $.modal.all.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_cancel'); ?>': {
              classes:  'glossy',
              click:    function(win) { $.modal.all.closeModal(); }
            },
            '<?php echo $lC_Language->get('button_next'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                mask();
                nvp = $('#sAdd').serialize();
                var groupText = $('#group').find(":selected").text();
                var groupID = $('#group').find(":selected").val();
                var counter = 2;
                set = groupText;
                // get the entry data
                var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getSimpleOptionEntryData&NVP'); ?>'
                $.getJSON(jsonLink.replace('NVP', nvp),
                  function (edata) {
                    unmask();
                    if (edata.rpcStatus == -10) { // no session
                      var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                      $(location).attr('href',url);
                    }
                    if (edata.rpcStatus != 1) {
                      if (edata.rpcStatus == -2) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_no_variant_entries'); ?>');
                      } else {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                      }
                      return false;
                    }   
                    
                    $.modal({
                        content: '<div id="addComboOptionEntry">'+
                                 '  <div id="addComboOptionEntryForm">'+
                                 '    <form name="seAdd" id="seAdd" action="" method="post">'+
                                 '      <p class="button-height block-label">'+
                                 '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_option_items'); ?></label>'+
                                 '        <p class="silver-bg with-small-padding big-text">&nbsp;' + groupText + '</p>'+
                                 '        <div class="relative">'+
                                 '          <div id="entrySelectContainer" class="visual" style="width:85%;"></div>'+
                                 '          <div id="entryVisualContainer" class="visual" style="width:10%; position:absolute; top:4px; right:0;"></div>'+
                                 '        </div>'+
                                 '      </p>'+
                                 '      <p class="button-height block-label">'+
                                 '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_another_group'); ?></label>'+
                                 '        <span id="groupSelectComboContainer' + counter.toString() + '"></span>'+
                                 '      </p>'+
                                 '    </form>'+
                                 '  </div>'+
                                 '</div>',
                        title: '<?php echo $lC_Language->get('modal_heading_new_combo_option'); ?>',
                        width: 320,
                        actions: {
                          'Close' : {
                            color: 'red',
                            click: function(ewin) { $.modal.all.closeModal(); }
                          }
                        },
                        buttons: {
                          '<?php echo $lC_Language->get('button_back'); ?>': {
                            classes:  'glossy',
                            click:    function(ewin) { ewin.closeModal(); }
                          },                      
                          '<?php echo $lC_Language->get('button_cancel'); ?>': {
                            classes:  'glossy',
                            click:    function(ewin) {  $.modal.all.closeModal() }
                          },
                          '<?php echo $lC_Language->get('button_next'); ?>': {
                            classes:  'blue-gradient glossy',
                            click:    function(ewin) {
                              var bValid = $("#seAdd").validate({
                                rules: {
                                },
                                invalidHandler: function() {
                                }
                              }).form();
                              if (bValid) {
                                counter++;
                                var nvp = $('#seAdd').serialize();
                                var groupText2 = $('#group_2').find(":selected").text();
                                var groupID2 = $('#group_2').find(":selected").val();
                                set = set + ', ' + groupText2;
                                
                                var products_price = '<?php echo ((isset($pInfo)) ? number_format($pInfo->get('products_price'), DECIMAL_PLACES) : 0.00); ?>';
                                var products_weight = '<?php echo ((isset($pInfo)) ? number_format($pInfo->get('products_weight'), 4) : 0.0000); ?>';
                                var products_status = '<?php echo ((isset($pInfo)) ? (int)$pInfo->get('products_status') : 0.00); ?>';
                                
                                $.modal({
                                    content: '<div id="addComboOptionEntry2">'+
                                             '  <div id="addComboOptionEntryForm2">'+
                                             '    <form name="seAdd2" id="seAdd2" action="" method="post">'+
                                             '      <p class="button-height block-label">'+
                                             '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_combo_group_variants'); ?><small class="float-right mid-padding-top">VISUALS</small></label>'+
                                             '        <p class="silver-bg with-small-padding big-text">&nbsp;' + groupText + '<span class="float-right small-margin-right"><input type="checkbox" style="vertical-align:0%;" class="input chk" onchange="setDefaultVisual(this);" name="visual[' + groupID + ']" checked></span></p>'+
                                             '        <div class="relative">'+
                                             '          <div id="entrySelectContainer2" class="visual" style="width:85%;"></div>'+
                                             '          <div id="entryVisualContainer2" class="visual" style="width:10%; position:absolute; top:4px; right:0;"></div>'+
                                             '        </div><div class="clear-both"></div>'+
                                             '      </p>'+
                                             '      <p class="button-height block-label">'+
                                             '        <label for="group" class="label small-margin-bottom"></label>'+
                                             '        <p class="silver-bg with-small-padding big-text">&nbsp;' + groupText2 + '<span class="float-right small-margin-right"><input type="checkbox" style="vertical-align:0%;" class="input chk" onchange="setDefaultVisual(this);" name="visual[' + groupID2 + ']"></span></p>'+
                                             '        <div class="relative">'+
                                             '          <div id="entrySelectContainer3" class="visual" style="width:85%;"></div>'+
                                             '          <div id="entryVisualContainer3" class="visual" style="width:10%; position:absolute; top:4px; right:0;"></div>'+
                                             '        </div><div class="clear-both"></div>'+
                                             '      </p>'+                                             
                                             '      <p class="block-label"><?php echo strtoupper($lC_Language->get('text_or')); ?></p>'+
                                             '      <p class="button-height block-label">'+
                                             '        <a href="javascript:void(0);" onclick="addOptionGroup(counter);" class="button"><?php echo $lC_Language->get('button_add_another_group'); ?></a>'+
                                             '      </p><hr>'+
                                             '      <p class="button-height block-label">'+
                                             '        <label for="use_product_price" class="label small-margin-bottom">'+
                                             '        <?php echo lc_draw_checkbox_field('use_product_price', null, true, 'class="switch tiny" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
                                             '        <?php echo $lC_Language->get('field_use_product_price'); ?></label>'+
                                             '      </p>'+                                             
                                             '      <p class="button-height block-label">'+
                                             '        <label for="use_product_weight" class="label small-margin-bottom">'+
                                             '        <?php echo lc_draw_checkbox_field('use_product_weight', null, true, 'class="switch tiny" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
                                             '        <?php echo $lC_Language->get('field_use_product_weight'); ?></label>'+
                                             '      </p>'+                                              
                                             '      <p class="button-height block-label">'+
                                             '        <label for="use_product_status" class="label small-margin-bottom">'+
                                             '        <?php echo lc_draw_checkbox_field('use_product_status', null, true, 'class="switch tiny" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>'+
                                             '        <?php echo $lC_Language->get('field_use_product_status'); ?></label>'+
                                             '      </p>'+                                              
                                             '      <p class="button-height inline-label">'+
                                             '        <label for="set_product_qty" class="label no-wrap"><?php echo $lC_Language->get('field_set_qoh_for_each'); ?></label>'+
                                             '        <?php echo lc_draw_input_field('set_product_qty', '100', 'id="edit-first_name" class="input" style="width:25%; float:right; margin-right:119px;"'); ?>'+
                                             '      </p>'+  
                                             '      <input type="hidden" name="products_price" value="' + products_price + '">'+                                           
                                             '      <input type="hidden" name="products_weight" value="' + products_weight + '">'+                                           
                                             '      <input type="hidden" name="products_status" value="' + products_status + '">'+                                           
                                             '    </form>'+
                                             '  </div>'+
                                             '</div>',
                                    title: '<?php echo $lC_Language->get('modal_heading_new_combo_option'); ?>',
                                    width: 320,
                                    actions: {
                                      'Close' : {
                                        color: 'red',
                                        click: function(ewin) { $.modal.all.closeModal(); }
                                      }
                                    },
                                    buttons: {
                                      '<?php echo $lC_Language->get('button_back'); ?>': {
                                        classes:  'glossy',
                                        click:    function(ewin) { ewin.closeModal(); }
                                      },                      
                                      '<?php echo $lC_Language->get('button_cancel'); ?>': {
                                        classes:  'glossy',
                                        click:    function(ewin) { $.modal.all.closeModal(); }
                                      },
                                      '<?php echo $lC_Language->get('button_next'); ?>': {
                                        classes:  'blue-gradient glossy',
                                        click:    function(ewin) {
                                          var bValid = $("#seAdd2").validate({
                                            rules: {
                                            },
                                            invalidHandler: function() {
                                            }
                                          }).form();
                                          if (bValid) {
                                            counter++;
                                            var formData = $('#seAdd2').serializeJSON();
                                            
                                            formData.set = set;
                                            
                                            $.modal.all.closeModal();  
                                            
                                            // take a snapshot before any changes
                                            var snapshot = $('#comboOptionsTbody').html();
                                            $('#button-revert').show();
                                            
                                            // insert options rows on stage
                                            getOptionsRows(formData);  
                                            
                                            // get the number of variants created                                           
                                            var data = $('#seAdd2').serializeJSON();
                                            // calculate the number of variants
                                            var groups = new Array();
                                            $.each(data.combo, function(key, val) {
                                            if (val != undefined) {
                                                var parts = key.split('__');
                                                groups[parts[0]] = val.length;
                                              }
                                            }); 
                                            var cnt = 1;
                                            var variants = 0;
                                            $.each(groups, function(k, v) {
                                              if (v != undefined) {
                                                if (cnt == 1) {
                                                  variants = v;
                                                  cnt++;
                                                } else {
                                                  variants = variants * v;
                                                }
                                              }
                                            })                                            
                                            
                                            $.modal({
                                                content: '<div id="addComboOptionConfirm">'+
                                                         '  <div id="addComboOptionConfirmForm">'+
                                                         '    <h4><?php echo $lC_Language->get('field_complete'); ?></h4>'+
                                                         '    <div id="subHeadingContainer" class="relative">'+
                                                         '      <div class="float-left">'+
                                                         '        <div class="small-margin-bottom">Set Created</div>'+
                                                         '        <div>Inventory Variants</div>'+
                                                         '      </div>'+
                                                         '      <div class="float-right" style="width:60%;">'+
                                                         '        <div class="strong small-margin-bottom">' + set + '</div>'+
                                                         '        <div class="strong">' + variants.toString() + '</div>'+
                                                         '      </div>'+                                                         
                                                         '    </div><div class="clear-both"></div>'+
                                                         '    <p class="message orange-gradient icon-warning mid-margin-top" style="text-align:justify;"><?php echo $lC_Language->get('ms_warning_options_set_complete'); ?></p>'+
                                                         '    <form name="optConfirm" id="optnConfirm" action="" method="post">'+
                                                         '      <p class="align-center small-padding-bottom small-padding-top">'+
                                                         '        <span class="button-group">'+
                                                         '          <label for="status-1" class="button green-active">'+
                                                         '            <input type="radio" name="status" id="status-1" onclick="toggleComboOptionsStatus(\'on\');">'+
                                                         '            <?php echo $lC_Language->get('button_active'); ?>'+
                                                         '          </label>'+
                                                         '          <label for="status-2" class="button red-active">'+
                                                         '            <input type="radio" name="status" id="status-2" onclick="toggleComboOptionsStatus(\'off\');" checked>'+
                                                         '            <?php echo $lC_Language->get('button_inactive'); ?>'+
                                                         '          </label>'+
                                                         '        </span>'+     
                                                         '      </p>'+                                             
                                                         '      <p class="message blue-gradient icon-info-round mid-margin-top">&nbsp;<?php echo $lC_Language->get('ms_info_options_set_pricing'); ?></p>'+
                                                         '    </form>'+
                                                         '  </div>'+
                                                         '</div>',
                                                title: '<?php echo $lC_Language->get('modal_heading_new_combo_option'); ?>',
                                                width: 320,
                                                actions: {
                                                  'Close' : {
                                                    color: 'red',
                                                    click: function(fwin) { $('#comboOptionsTbody').html(snapshot); $('#button-revert').show(); $.modal.all.closeModal(); }
                                                  }
                                                },
                                                buttons: {
                                                  '<?php echo $lC_Language->get('button_cancel'); ?>': {
                                                    classes:  'glossy',
                                                    click:    function(fwin) { $('#comboOptionsTbody').html(snapshot); $('#button-revert').show(); $.modal.all.closeModal(); }
                                                  },
                                                  '<?php echo $lC_Language->get('button_done'); ?>': {
                                                    classes:  'blue-gradient glossy',
                                                    click:    function(fwin) {
                                                      // done
                                                      fwin.closeModal();  
                                                    }
                                                  }
                                                },
                                                buttonsLowPadding: true
                                            });                                            
                                            
                                          }                              
                                        }
                                      }
                                    },
                                    buttonsLowPadding: true
                                }); 
                                
                                var visual = '';
                                $.each(edata, function(key, val) {
                                  if (val.id != undefined) {
                                    visual = visual + val.visual;
                                  }
                                });

                                // get the selected values in csv string
                                var values = $("#combo-" + groupID).val() || [];

                                $("#entrySelectContainer2").html('<select id="combo-' + groupID + '" name="combo[' + groupID + '][]" class="select check-list full-width easy-multiple-selection" multiple>' + getGroupsSelectOptions(edata, values) + '</select>').change();                    
                                $("#entryVisualContainer2").html(visual);  

                                getOptionsData(groupID2, function(fdata) {
                                  $("#entrySelectContainer3").html('<select id="combo-' + groupID2 + '" name="combo[' + groupID2 + '][]" class="select check-list full-width easy-multiple-selection" multiple>' + getGroupsSelectOptions(fdata) + '</select>').change();                    
                                  $("#entryVisualContainer3").html(fdata.visual);  
                                  $.modal.all.centerModal();                              
                                });  
                              }                              
                            }
                          }
                        },
                        buttonsLowPadding: true
                    }); 
                    
                    var visual = '';
                    $.each(edata, function(key, val) {
                      if (val.id != undefined) {
                        visual = visual + val.visual;
                      }
                    });
                    $("#entrySelectContainer").html('<select id="combo-' + groupID + '" name="combo[' + groupID + '][]" class="select check-list full-width easy-multiple-selection" multiple>' + getGroupsSelectOptions(edata) + '</select>').change();                    
                    $("#entryVisualContainer").html(visual);  
                    $("#groupSelectComboContainer" + counter.toString()).html('<select id="group_' + counter.toString() + '" name="group[' + counter.toString() + ']" class="select multiple full-width">' +  getGroupsSelectOptions(data) + '</select>').change();                    
                  }
                );                               
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#groupSelectContainer").html('<select id="group" name="group" class="select multiple full-width">' +  getGroupsSelectOptions(data) + '</select>').change();                    
    }
  );
}
</script>