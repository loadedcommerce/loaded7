<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: newEntry.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#newEntry { padding-bottom:20px; }
</style>
<script>
function newEntry(zid) {
  var accessLevel = '<?php echo $_SESSION['admin']['access']['locale']; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getEntryFormData'); ?>'
  $.getJSON(jsonLink,
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      $("#status-working").fadeOut('slow');
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="newEntry">'+
                   '  <div id="newEntryForm">'+
                   '    <form name="zNewEntry" id="zNewEntry" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_zone_entry'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="zone_country_id" class="label"><?php echo $lC_Language->get('field_country'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('zone_country_id', null, null, 'onchange="updateZones();" class="input with-small-padding" style="width:73%"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="zone_id" class="label"><?php echo $lC_Language->get('field_zone'); ?></label>'+
                   '        <?php echo  lc_draw_pull_down_menu('zone_id', null, null, 'class="input with-small-padding" style="width:73%"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_zone_entry'); ?>',
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

              var bValid = $("#zNewEntry").validate({
                rules: {
                  zone_name: { required: true },
                  zone_code: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#zNewEntry").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&group_id=ZID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('ZID', zid).replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (rdata.rpcStatus != 1) {
                        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
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
      $("#zone_country_id").empty();
      i = 0
      $.each(data.countriesArray, function(val, text) {
        if(i == 0) {
          $("#zone_country_id").closest("span + *").prevAll("span.select-value:first").text(text);
          i++;
        }
        $("#zone_country_id").append(
          $("<option></option>").val(val).html(text)
        );
      });
      $("#zone_id").empty();
      i = 0;
      $.each(data.zonesArray, function(val, text) {
        if(i == 0) {
          $("#zone_id").closest("span + *").prevAll("span.select-value:first").text(text);
          i++;
        }
        $("#zone_id").append(
          $("<option></option>").val(val).html(text)
        );
      });

    }
  );
}

function updateZones(selected) {
  mask();
  var countryID = $("#zone_country_id").val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getZones&country_id=CID'); ?>'
  $.getJSON(jsonLink.replace('CID', countryID),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        unmask();
        if (data.rpcStatus == -1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
      } else {

      $("#zone_id").empty();
      $.each(data.zonesArray, function(val, text) {
        $("#zone_id").append(
          $("<option></option>").val(val).html(text)
        );
      });

        if (selected != undefined) {
          $("#zone_id").val( selected ).attr('selected', true);
        }
        unmask();
      }
    }
  );
}
</script>