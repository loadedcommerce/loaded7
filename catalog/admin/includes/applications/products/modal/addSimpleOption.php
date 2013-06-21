<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Currencies;
?>
<style>
#addSimpleOption { padding-bottom:20px; }
</style>
<script>
function addSimpleOption(editRow) {
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
  function getNewOptionsRow(id, groups, entries, selected) {
    var groupTitle = '';
    var groupModule = '';
    var groupLanguageID = '1';
    $.each(groups, function(key, val) {
      if (val.id == id) {
        groupTitle = val.title;
        groupModule = val.module;
        groupLanguageID = val.languages_id;
      }
    }); 
    
    var items = '';
    var itemsInput = '';
    var pitemsInput = '';
    var ref = Math.floor((1 + Math.random()) * 0x10000).toString(16);   
    $.each(entries, function(key, entry) {
      if (entry.title != undefined) {
        var curSymbol = '<?php echo $lC_Currencies->getSymbolLeft(); ?>';
        var check = 'entry=' + entry.id;
        if (selected.indexOf(check) > 0) {  // is item in the selected list
          items += '<div class="small"><span class="icon-right icon-blue with-small-padding"></span>' + entry.title + '</div>';
          itemsInput += '<input type="hidden" id="simple_options_entry_' + id + '_' + entry.id + '" name="simple_options_entry[' + id + '][' + entry.id + ']" value="' + entry.title + '">';
          pitemsInput += '<tr class="trp-' + id + '">'+
                         '  <td class="element">' + entry.title + '</td>'+
                         '  <td>'+
                         '    <div id="div_CGROUP_' + id + '_' + entry.id + '" class="icon-plus-round icon-green icon-size2" style="display:inline;">'+
                         '      <div class="inputs" style="display:inline; padding:8px 0;">'+
                         '        <span class="mid-margin-left no-margin-right">' + curSymbol + '</span>'+
                         '        <input type="text" class="input-unstyled" value="' + entry.price_modifier.toFixed(2) + '" onblur="showSimpleOptionsPricingSymbol(this, \'' + 'CGROUP_' + id + '_' + entry.id + '\');" id="simple_options_entry_price_modifier_CGROUP_' + id + '_' + entry.id + '" name="simple_options_entry_price_modifier[CGROUP][' + id + '][' + entry.id + ']">'+
                         '      </div>'+
                         '    </div>'+
                         '  </td>'+
                         '</tr>';
        }
      }
    });    
              
    var row = '<tr id="tre-' + ref + '">'+
              '  <td width="16px" style="cursor:move;"><span class="icon-list icon-grey icon-size2"></span></td>'+
              '  <td width="16px" style="cursor:pointer;" onclick="toggleSimpleOptionsRow(\'#drope' + ref + '\');"><span id="drope' + ref + '_span" class="toggle-icon icon-squared-plus icon-grey icon-size2"></span></td>'+
              '  <td width="40%">' + groupTitle + '<div class="small-margin-top dropall" id="drope' + ref + '" style="display:none;"><span>' + items + '</span></div></td>'+
              '  <td width="30%" class="hide-below-480">' + groupModule + '</td>'+
              '  <td width="10%" class="sort hide-below-480"></td>'+
              '  <td width="15%" align="center" style="cursor:pointer;" onclick="toggleSimpleOpitonsStatus(this, \'' + id + '\');"><span class="icon-tick icon-size2 icon-green"></span></td>'+
              '  <td width="15%" align="right">'+
              '     <span class="icon-pencil icon-orange icon-size2 margin-right with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"left"}\' title="Edit Entry" style="cursor:pointer;" onclick="addSimpleOption(\'' + id + '\')"></span>'+
              '     <span class="icon-trash icon-size2 icon-red with-tooltip" data-tooltip-options=\'{"classes":["grey-gradient"],"position":"right"}\' title="Remove Entry" style="cursor:pointer;" onclick="removeSimpleOptionsRow(\'' + id + '\');"></span>'+
              '   </td>'+
              '  <input type="hidden" name="simple_options_group_name[' + id + ']" value="' + groupTitle + '">'+
              '  <input type="hidden" name="simple_options_group_type[' + id + ']" value="' + groupModule + '">'+
              '  <input class="sort" type="hidden" name="simple_options_group_sort_order[' + id + ']" value="">'+
              '  <input type="hidden" id="simple_options_group_status_' + id + '" name="simple_options_group_status[' + id + ']" value="1">' + itemsInput +
              '</tr>';              
              
    var prow = '<tr id="trp-' + ref + '" class="trp-' + ref + '"><td width="100px" class="strong">' + groupTitle + '</td></tr>' + pitemsInput;
     
    // if the group already exists, remove it before adding
    $("#simpleOptionsTable tr td:contains('" + groupTitle + "')").each(function() {
      $(this).closest('tr').remove();
    });
    // also remove it on the pricing table
    $("#simpleOptionsPricingTable tr td:contains('" + groupTitle + "')").each(function() {
      $(this).closest('tr').remove();
      $('.trp-' + id).remove();
    });   
    //          
    $('#simpleOptionsTable > tbody').append(row);
    
    var customerGroups = <?php echo json_encode(lC_Customer_groups_Admin::getAll()); ?>; 
    $.each(customerGroups.entries, function(key, val) { 
      var regex = new RegExp('CGROUP', 'g');   
      $('#tbody-' + val.customers_group_id).append(prow.replace(regex, val.customers_group_id)); 
    });
  }   
  
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
          content: '<div id="addSimpleOption">'+
                   '  <div id="addSimpleOptionForm">'+
                   '    <form name="sAdd" id="sAdd" action="" method="post">'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="filter" class="label" style="width:47%"><?php echo $lC_Language->get('field_filter_by_class'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('filter', array(array('id' => '0', 'text' => 'Common')), null, 'style="width:81%;" class="input with-small-padding"') . lc_go_pro(true); ?>'+
                   '      </p>'+
                   '      <p class="button-height block-label">'+
                   '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_group'); ?></label>'+
                   '        <span id="groupSelectContainer"></span>'+
                   '      </p>'+
                   '      <p class="button-height block-label no-margin-bottom strong">OR'+
                   '        <div><a href="javsscript(void);" onclick="alert(\'Feature Not Present\');" class="button icon-plus-round green-gradient glossy"><?php echo $lC_Language->get('button_add_new_group'); ?></a><?php echo lc_go_pro(true); ?></div>'+
                   '      </p>'+                   
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_simple_option'); ?>',
          width: 320,
          scrolling: false,
          actions: {
            'Close' : {
              color: 'red',
              click: function(win) { win.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_cancel'); ?>': {
              classes:  'glossy',
              click:    function(win) { win.closeModal(); }
            },
            '<?php echo $lC_Language->get('button_next'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                mask();
                nvp = $('#sAdd').serialize();
                var groupText = $('#group').find(":selected").text();
                var groupID = $('#group').find(":selected").val();
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
                      $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                      return false;
                    }                
                    $.modal({
                        content: '<div id="addSimpleOptionEntry">'+
                                 '  <div id="addSimpleOptionEntryForm">'+
                                 '    <form name="seAdd" id="seAdd" action="" method="post">'+
                                 '      <p class="button-height inline-label">'+
                                 '        <label for="filter" class="label" style="width:47%"><?php echo $lC_Language->get('field_filter_by_class'); ?></label>'+
                                 '        <?php echo lc_draw_pull_down_menu('efilter', array(array('id' => '0', 'text' => 'Common')), null, 'style="width:81%;" class="input with-small-padding"') . lc_go_pro(true); ?>'+
                                 '      </p>'+
                                 '      <p class="button-height block-label">'+
                                 '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_option_items'); ?></label>'+
                                 '        <p class="silver-bg with-small-padding big-text ">&nbsp;' + groupText + '</p>'+
                                 '        <span id="entrySelectContainer"></span>'+
                                 '      </p>'+
                                 '      <p class="button-height block-label no-margin-bottom strong">OR'+
                                 '        <div><a href="javsscript(void);" onclick="alert(\'Feature Not Present\');" class="button icon-plus-round green-gradient glossy"><?php echo $lC_Language->get('button_add_new_item'); ?></a><?php echo lc_go_pro(true); ?></div>'+
                                 '      </p>'+                   
                                 '    </form>'+
                                 '  </div>'+
                                 '</div>',
                        title: '<?php echo $lC_Language->get('modal_heading_new_simple_option'); ?>',
                        width: 320,
                        scrolling: false,
                        actions: {
                          'Close' : {
                            color: 'red',
                            click: function(ewin) { ewin.closeModal(); }
                          }
                        },
                        buttons: {
                          '<?php echo $lC_Language->get('button_back'); ?>': {
                            classes:  'glossy',
                            click:    function(ewin) { ewin.closeModal(); }
                          },                      
                          '<?php echo $lC_Language->get('button_cancel'); ?>': {
                            classes:  'glossy',
                            click:    function(ewin) { win.closeModal(); ewin.closeModal(); }
                          },
                          '<?php echo $lC_Language->get('button_done'); ?>': {
                            classes:  'blue-gradient glossy',
                            click:    function(ewin) {
                              var bValid = $("#seAdd").validate({
                                rules: {
                                  entry: { required: true }
                                },
                                invalidHandler: function() {
                                }
                              }).form();
                              if (bValid) {

                                var sel = $('#seAdd').serialize();
                                getNewOptionsRow(groupID, data, edata, sel);
                                win.closeModal();
                                ewin.closeModal();
                                _setSimpleOptionsSortOrder();
                                
                              }                              
                            }
                          }
                        },
                        buttonsLowPadding: true
                    }); 
                    
                    var entries = '';
                    var row = (editRow != undefined) ? editRow : '';
                    $.each(edata, function(key, val) {
                      if (val.id != undefined) {
                        if ($("#simple_options_entry_" + row.toString() + "_" + val.id.toString()).length > 0) {
                          entries += '<option selected="selected" value="' + val.id + '">' + val.title + '</option>';
                        } else {
                          entries += '<option value="' + val.id + '">' + val.title + '</option>';
                        }
                      }
                    });
                    $("#entrySelectContainer").html('<select id="entry" name="entry" class="select check-list easy-multiple-selection full-width" multiple>' +  entries + '</select>').change();                    
                  }
                );                               
                
              }
            }
          },
          buttonsLowPadding: true
      });
      
      var options = '';
      var row = (editRow != undefined) ? editRow : '';
      $.each(data, function(key, val) {
        if (val.id != undefined) {
          if (val.id == row) {
            options += '<option selected="selected" value="' + val.id + '">' + val.title + '</option>';
          } else {
            options += '<option value="' + val.id + '">' + val.title + '</option>';
          }
        }
      });
      $("#groupSelectContainer").html('<select id="group" name="group" class="select multiple check-list full-width">' +  options + '</select>').change();
      
    }
  );
}
</script>