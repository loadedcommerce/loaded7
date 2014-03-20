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
global $lC_Language, $lC_Template, $lC_Currencies;
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
            options += '<option selected="selected" value="' + val.id + '">' + val.title + '</option>';
          } else {
            options += '<option value="' + val.id + '">' + val.title + '</option>';
          }                    
          cnt++;
        }
      });    
    } else {

      var v = s.toString();
      $.each(d, function(key, val) {
        if (val.id != undefined) { 
          if (v.indexOf(val.id) != -1) {
            options += '<option selected="selected" value="' + val.id + '">' + val.title + '</option>';
          } else {
            options += '<option value="' + val.id + '">' + val.title + '</option>';
          }                   
        }   
      });   
    }
    
    return options;
  }
  
  function getOptionsRows(nvp) {
    mask();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getComboRowData&addon=Loaded_7_Pro&NVP'); ?>'
    $.getJSON(jsonLink.replace('NVP', nvp),
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
alert(print_r(data, true));        
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
                                            var formDataNVP = $('#seAdd2').serialize();
                                            formData.set = set;
                                            
                                            $.modal.all.closeModal(); 
                                            
                                            // insert options rows on stage
                                            getOptionsRows(formDataNVP);                                             

                                            // calculate the number of variants
                                            var groups = new Array();
                                            $.each(formData.combo, function(key, val) {
                                            if (val != undefined) {
                                                groups[key] = val.length;
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
                                            });                                           
                                            
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
                                                         '            <input type="radio" name="status" id="status-1">'+
                                                         '            <?php echo $lC_Language->get('button_active'); ?>'+
                                                         '          </label>'+
                                                         '          <label for="status-2" class="button red-active">'+
                                                         '            <input type="radio" name="status" id="status-2" checked>'+
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
                                                    click: function(fwin) { $.modal.all.closeModal(); }
                                                  }
                                                },
                                                buttons: {
                                                  '<?php echo $lC_Language->get('button_cancel'); ?>': {
                                                    classes:  'glossy',
                                                    click:    function(fwin) { $.modal.all.closeModal(); }
                                                  },
                                                  '<?php echo $lC_Language->get('button_done'); ?>': {
                                                    classes:  'blue-gradient glossy',
                                                    click:    function(fwin) {
                                                      counter++;
                                                        
                                                        
                                                      alert('set status ... done');
                                                      
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

function setDefaultVisual(e) {   
  $('.chk').removeAttr('checked');
  $(e).prop("checked", true);
}
</script>