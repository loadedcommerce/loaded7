<?php
/*
  $Id: updateRates.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function updateRates() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getRatesData'); ?>'
  $.getJSON(jsonLink,
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
          content: '<div id="updateRates">'+
                   '  <div id="updateRatesForm">'+
                   '    <form name="cUpdate" id="cUpdate" autocomplete="off" action="" method="post">'+
                   '      <p><?php echo $lC_Language->get('introduction_update_exchange_rates'); ?></p>'+
                   '      <p class="button-height" id="updateRatesServices"></p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_update_currency_rates'); ?>',
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
            '<?php echo $lC_Language->get('button_update'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#cUpdate").validate({
                rules: {
                },
                invalidHandler: function() {
                }
              }).form();
              if (bValid) {
                  var nvp = $("#cUpdate").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateRates&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('BATCH', nvp),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      if (data.updatedString.length > 0) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_success_currency_updated'); ?>' + '<strong> ' + data.updatedString + ' </strong');
                      }
                      if (data.notUpdatedString.length > 0) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_invalid_currency'); ?><strong> ' + data.notUpdatedString + ' </strong> <?php echo $lC_Language->get('ms_error_invalid_currency2'); ?>');
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
      $("#updateRatesServices").html(data.ratesSelection);
      $("#service_1")[0].checked = true;
    }
  );
}
</script>