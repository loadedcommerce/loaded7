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
#editEntry { padding-bottom:20px; }
</style>
<script>
function editEntry(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getEntryFormData&zaid=ZAID'); ?>'
  $.getJSON(jsonLink.replace('ZAID', id),
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
          content: '<div id="editEntry">'+
                 '  <div id="editEntryForm">'+
                 '    <form name="zEditEntry" id="zEditEntry" autocomplete="off" action="" method="post">'+
                 '      <p><?php echo $lC_Language->get('introduction_edit_zone_entry'); ?></p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="zone_country_id" class="label"><?php echo $lC_Language->get('field_country'); ?></label>'+
                 '        <?php echo lc_draw_pull_down_menu('zone_country_id', null, null, 'class="input with-small-padding" style="width:73%;" id="editZoneCountryId" onchange="updateZonesEdit();"'); ?>'+
                 '      </p>'+
                 '      <p class="button-height inline-label">'+
                 '        <label for="zone_id" class="label"><?php echo $lC_Language->get('field_zone'); ?></label>'+
                 '        <?php echo  lc_draw_pull_down_menu('zone_id', null, null, 'class="input with-small-padding" style="width:73%; border:1px solid #999; background:#f9f9f9;" id="editZoneId"'); ?>'+
                 '      </p>'+
                 '    </form>'+
                 '  </div>'+
                 '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_zone_entry'); ?>',
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
                var bValid = $("#zEditEntry").validate({
                rules: {
                  zone_country_id: { required: true },
                  zone_id: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#zEditEntry").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&zaid=ZAID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('ZAID', id).replace('BATCH', nvp),
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
      $("#editZoneCountryId").empty();
      $.each(data.countriesArray, function(val, text) {
        var selected = (data.zoneData.zone_country_id == val) ? 'selected="selected"' : '';
        if(data.zoneData.zone_country_id == val) {
          $("#editZoneCountryId").closest("span + *").prevAll("span.select-value:first").text(text);         
        }
        $("#editZoneCountryId").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      updateZonesEdit(data.zoneData.zone_id);
    }
  );
}     

function updateZonesEdit(selected) { 
  $("#editEntryFormProcessing").fadeIn('fast');
  var countryID = $("#editZoneCountryId").val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getZones&country_id=CID'); ?>'
  $.getJSON(jsonLink.replace('CID', countryID),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $("#editEntryFormProcessing").fadeOut('slow');
        if (data.rpcStatus == -1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
      } else {
          
        i = 0;
        zoneOptions = '';
    //    $("#editZoneId").empty();
        $.each(data.zonesArray, function(val, text) {
  //        if(i == 0) {
  //          $("#editZoneId").closest("span + *").prevAll("span.select-value:first").text(text); 
  //          i++;
  //        }  
            if (val == selected) { 
              zoneOptions += '<option selected="selected" value="' + val + '">' + text + '</option>';
            } else {
              zoneOptions += '<option value="' + val + '">' + text + '</option>';
            }
        });   
        $("#editZoneId").html(zoneOptions).change();

      }
    }
  );
}
</script>