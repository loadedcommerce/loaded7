<?php
/*
  $Id: new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#newDefinition { padding-bottom:20px; }
</style>
<script>
function newDefinition() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&action=getDefinitionsFormData'); ?>'
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
          content: '<div id="newDefinition">'+
                   '  <div id="newDefinitionForm">'+
                   '    <form name="lNew" id="lNew" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_new_language_definition'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="key" class="label" style="width:31% !important;"><?php echo $lC_Language->get('field_definition_key'); ?></label>'+
                   '        <?php echo lc_draw_input_field('key', null, 'class="input" style="width:90%;"'); ?><span class="icon-info-round icon-blue with-tooltip small-margin-left" title="<?php echo $lC_Language->get('tooltip_definition_key'); ?>" style="cursor:pointer;" data-tooltip-options=\'{"classes":["anthracite-gradient"]}\'></span>'+
                   '      </p>'+
                   '      <p class="inline-label">'+
                   '        <label for="value" class="label" style="width:31% !important; padding-top:5px;"><?php echo $lC_Language->get('field_definition_value'); ?></label>'+
                   '        <span id="definitionValue"></span><span class="icon-info-round icon-blue with-tooltip small-margin-left" title="<?php echo $lC_Language->get('tooltip_definition_value'); ?>" style="cursor:pointer;" data-tooltip-options=\'{"classes":["anthracite-gradient"]}\'></span>'+
                   '      </p>'+
                   '      <p class="inline-label">'+
                   '        <label for="value" class="label" style="width:31% !important;"><?php echo $lC_Language->get('field_definition_group'); ?></label>'+
                   '        <span id="groupSelection"></span><span class="icon-info-round icon-blue with-tooltip small-margin-left" title="<?php echo $lC_Language->get('tooltip_definition_group'); ?>" style="cursor:pointer;" data-tooltip-options=\'{"classes":["anthracite-gradient"]}\'></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="group_new" class="label" style="width:31% !important;"><?php echo $lC_Language->get('field_definition_new_group'); ?></label>'+
                   '        <?php echo lc_draw_input_field('group_new', null, 'class="input" style="width:90%;"'); ?><span class="icon-info-round icon-blue with-tooltip small-margin-left" title="<?php echo $lC_Language->get('tooltip_new_definition_group'); ?>" style="cursor:pointer;" data-tooltip-options=\'{"classes":["anthracite-gradient"]}\'></span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_new_language_definition'); ?>',
          width: 500,
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
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {

              var bValid = $("#lNew").validate({
                rules: {
                  key: { required: true },
                  value: { required: true }
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#lNew").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=insertDefinition&group=' . $_GET['group'] . '&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('BATCH', nvp),
                    function (rdata) {
                      if (rdata.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
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
      $("#definitionValue").html(data.definitionValue);
      $("#groupSelection").html(data.groupsSelection);
    }
  );
}
</script>