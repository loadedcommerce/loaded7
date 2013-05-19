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
?>
<style>
#addSimpleOption { padding-bottom:20px; }
</style>
<script>
function addSimpleOption(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  mask();
  function getNewOptionsRow(id, groups, entries, selected) {
    var groupTitle = '';
    var groupModule = '';
    //var groupSortOrder = '0';
    var groupLanguageID = '1';
    $.each(groups, function(key, val) {
      if (val.id == id) {
        groupTitle = val.title;
        groupModule = val.module;
    //    groupSortOrder = val.sort_order;
        groupLanguageID = val.languages_id;
      }
    }); 
    
    var items = '';
    var itemsInput = '';
    $.each(entries, function(key, entry) {
      if (entry.title != undefined) {
        var check = 'entry=' + entry.id;
        if (selected.indexOf(check) > 0) {  // is item in the selected list
          items += '<div class="small"><span class="icon-right icon-blue with-small-padding"></span>' + entry.title + '</div>';
          itemsInput += '<input type="hidden" name="simple_options_entry[' + id + '][' + entry.id + ']" value="' + entry.title + '">';
        }
      }
    });    
    
    var ref = Math.floor((1 + Math.random()) * 0x10000).toString(16);   

    var row = '<tr id="tr-' + ref + '" style="cursor:pointer;">'+
              '  <td><img src="templates/default/img/icons/16/drag.png"></td>'+
              '  <td onclick="$(\'.drop' + ref + '\').toggle();">' + groupTitle + '<div class="small-margin-top drop' + ref + '" style="display:none;"><span>' + items + '</span></div></td>'+
              '  <td onclick="$(\'.drop' + ref + '\').toggle();">' + groupModule + '</td>'+
              '  <td class="sort" onclick="$(\'.drop' + ref + '\').toggle();"></td>'+
              '  <td align="center"><span class="icon-cross icon-size2 icon-red" onclick="$(\'#tr-' + ref + '\').remove();$(\'#drop' + ref + '\').remove();"></span></td>'+
              '  <input type="hidden" name="simple_options_group_name[' + id + ']" value="' + groupTitle + '">'+
              '  <input type="hidden" name="simple_options_group_type[' + id + ']" value="' + groupModule + '">'+
              '  <input class="sort" type="hidden" name="simple_options_group_sort_order[' + id + ']" value="0">'+
              '  <input type="hidden" name="simple_options_group_status[' + id + ']" value="1">'+ itemsInput +
              '</tr>';
              
    return row;
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
                   '        <?php echo lc_draw_pull_down_menu('filter', array(array('id' => '0', 'text' => 'Common')), null, 'style="width:81%;" class="input with-small-padding"') . lc_go_pro(); ?>'+
                   '      </p>'+
                   '      <p class="button-height block-label">'+
                   '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_group'); ?></label>'+
                   '        <span id="groupSelectContainer"></span>'+
                   '      </p>'+
                   '      <p class="button-height block-label no-margin-bottom strong">OR'+
                   '        <div><a href="javsscript(void);" onclick="alert(\'Feature Not Present\');" class="button icon-plus-round green-gradient glossy"><?php echo $lC_Language->get('button_add_new_group'); ?></a><?php echo lc_go_pro(); ?></div>'+
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
                                 '        <?php echo lc_draw_pull_down_menu('efilter', array(array('id' => '0', 'text' => 'Common')), null, 'style="width:81%;" class="input with-small-padding"') . lc_go_pro(); ?>'+
                                 '      </p>'+
                                 '      <p class="button-height block-label">'+
                                 '        <label for="group" class="label small-margin-bottom"><?php echo $lC_Language->get('field_select_option_items'); ?></label>'+
                                 '        <p class="silver-bg with-small-padding big-text ">&nbsp;' + groupText + '</p>'+
                                 '        <span id="entrySelectContainer"></span>'+
                                 '      </p>'+
                                 '      <p class="button-height block-label no-margin-bottom strong">OR'+
                                 '        <div><a href="javsscript(void);" onclick="alert(\'Feature Not Present\');" class="button icon-plus-round green-gradient glossy"><?php echo $lC_Language->get('button_add_new_item'); ?></a><?php echo lc_go_pro(); ?></div>'+
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
                                $('#simpleOptionsTable > tbody').append(getNewOptionsRow(groupID, data, edata, sel));
                                win.closeModal();
                                ewin.closeModal();
                                
                              }                              
                            }
                          }
                        },
                        buttonsLowPadding: true
                    }); 
                    
                    var entries = '';
                    $.each(edata, function(key, val) {
                      if (val.id != undefined) {
                        entries += '<option value="' + val.id + '">' + val.title + '</option>';
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
      $.each(data, function(key, val) {
        if (val.id != undefined) {
          options += '<option value="' + val.id + '">' + val.title + '</option>';
        }
      });
      $("#groupSelectContainer").html('<select id="group" name="group" class="select multiple check-list full-width">' +  options + '</select>').change();
      
    }
  );
}
</script>