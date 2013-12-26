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
#editLanguage { padding-bottom:20px; }
.label { width: 40% !important; }
</style>
<script>
function editLanguage(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&lid=LID'); ?>';
  $.getJSON(jsonLink.replace('LID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }

      $.modal({
          content: '<div id="editLanguage">'+
                   '  <div id="editLanguageForm">'+
                   '    <form name="lEdit" id="lEdit" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_language'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="name" class="label"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <?php echo lc_draw_input_field('name', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="code" class="label"><?php echo $lC_Language->get('field_code'); ?></label>'+
                   '        <?php echo lc_draw_input_field('code', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="locale" class="label"><?php echo $lC_Language->get('field_locale'); ?></label>'+
                   '        <?php echo lc_draw_input_field('locale', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="charset" class="label"><?php echo $lC_Language->get('field_character_set'); ?></label>'+
                   '        <?php echo lc_draw_input_field('charset', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="text_direction" class="label"><?php echo $lC_Language->get('field_text_direction'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('text_direction', array(array('id' => 'ltr', 'text' => 'ltr'), array('id' => 'rtl', 'text' => 'rtl')), null, 'class="input with-small-padding" style="min-width:200px;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_format_short" class="label"><?php echo $lC_Language->get('field_date_format_short'); ?></label>'+
                   '        <?php echo lc_draw_input_field('date_format_short', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_format_long" class="label"><?php echo $lC_Language->get('field_date_format_long'); ?></label>'+
                   '        <?php echo lc_draw_input_field('date_format_long', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="time_format" class="label"><?php echo $lC_Language->get('field_time_format'); ?></label>'+
                   '        <?php echo lc_draw_input_field('time_format', null, 'class="input" style="width:85%;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="currencies_id" class="label"><?php echo $lC_Language->get('field_currency'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('currencies_id', null, null, 'class="input with-small-padding" style="min-width:200px;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="numeric_separator_decimal" class="label"><?php echo $lC_Language->get('field_currency_separator_decimal'); ?></label>'+
                   '        <?php echo lc_draw_input_field('numeric_separator_decimal', null, 'class="input"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="numeric_separator_thousands" class="label"><?php echo $lC_Language->get('field_currency_separator_thousands'); ?></label>'+
                   '        <?php echo lc_draw_input_field('numeric_separator_thousands', null, 'class="input"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="parent_id" class="label"><?php echo $lC_Language->get('field_parent_language'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('parent_id', null, null, 'class="input with-small-padding" style="min-width:200px;"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="sort_order" class="label"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                   '        <?php echo lc_draw_input_field('sort_order', null, 'class="input"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label" id="setDefault"></p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_language'); ?>',
          width: 500,
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
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#lEdit").validate({
                  rules: {
                    name: { required: true },
                    code: { required: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var regex = new RegExp('%25', 'g');
                  var nvp = $("#lEdit").serialize().replace(regex, 'P_CENT');
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveLanguage&lid=LID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('LID', parseInt(id)).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      $("#status-working").fadeOut('slow');
                      if (rdata.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#currencies_id").empty();  // clear the old values
      $.each(data.currenciesArray, function(val, text) {
        var selected = (data.languageData.currencies_id == val) ? 'selected="selected"' : '';
        if(data.languageData.currencies_id == val) {
          $("#currencies_id").prevAll(".select-value:first").text(text);
        }
        $("#currencies_id").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
        

      $("#parent_id").empty();  // clear the old values
      $.each(data.languagesArray, function(val, text) {
        var selected = (data.languageData.languages_id == val) ? 'selected="selected"' : '';
        if (val == 0) {
          $("#parent_id").prevAll(".select-value:first").text(text);
        } else if(data.languageData.languages_id == val) {
          $("#parent_id").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#parent_id").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("#name").val(data.languageData.name);
      $("#code").val(data.languageData.code);
      $("#locale").val(data.languageData.locale);
      $("#charset").val(data.languageData.charset);
      $("#text_direction").val( data.languageData.text_direction ).attr('selected', true);
      $("#text_direction").closest("span + *").prevAll("span.select-value:first").text(data.languageData.text_direction);
      $("#date_format_short").val(data.languageData.date_format_short);
      $("#date_format_long").val(data.languageData.date_format_long);
      $("#time_format").val(data.languageData.time_format);
      $("#numeric_separator_decimal").val(data.languageData.numeric_separator_decimal);
      $("#numeric_separator_thousands").val(data.languageData.numeric_separator_thousands);
      $("#sort_order").val(data.languageData.sort_order);
      var defaultLanguage = '<?php echo DEFAULT_LANGUAGE; ?>';
      if (data.languageData.code != defaultLanguage) {
        $("#setDefault").html('<label for="default" class="label"><?php echo $lC_Language->get('field_set_default'); ?></label><?php echo '&nbsp;' . lc_draw_checkbox_field('default', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>');
      } else {
        $("#setDefault").empty();
      }
    }
  );
}
</script>