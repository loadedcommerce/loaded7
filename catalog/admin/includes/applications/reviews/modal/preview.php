<?php
/*
  $Id: preview.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<script>
function showPreview(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&rid=RID'); ?>';
  $.getJSON(jsonLink.replace('RID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      var modalContent = '<div id="showPreview">'+
                         '  <div id="showPreviewForm">'+
                         '    <p class="button-height inline-label">'+
                         '      <label for="products_name" class="label"><?php echo $lC_Language->get('field_product'); ?></label>'+
                         '      <span id="products_name"></span>'+
                         '    </p>'+
                         '    <p class="button-height inline-label">'+
                         '      <label for="customers_name" class="label"><?php echo $lC_Language->get('field_author'); ?></label>'+
                         '      <span id="customers_name"></span>'+
                         '    </p>'+
                         '    <p class="button-height inline-label">'+
                         '      <label for="date_added" class="label"><?php echo $lC_Language->get('field_date_added'); ?></label>'+
                         '      <span id="date_added"></span>'+
                         '    </p>'+
                         '    <p class="inline-label">'+
                         '      <label for="preview_text" class="label"><?php echo $lC_Language->get('field_review'); ?></label>'+
                         '      <span id="preview_text"></span>'+
                         '    </p>'+
                         '    <p class="button-height inline-label">'+
                         '      <label for="rating_stars" class="label"><?php echo $lC_Language->get('field_rating'); ?></label>'+
                         '      <span id="rating_stars"></span>'+
                         '    </p>'+
                         '    <p class="button-height inline-label">'+
                         '      <label for="rating_status" class="label"><?php echo $lC_Language->get('field_status'); ?></label>'+
                         '      <span id="rating_status"></span>'+
                         '    </p>'+
                         '  </div>'+
                         '</div>';
      if (data.rData.reviews_status == 1) {
        $.modal({
            content: modalContent,
            title: '<?php echo $lC_Language->get('modal_heading_preview_review'); ?>',
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
              '<?php echo $lC_Language->get('button_reject'); ?>': {
                classes:  'blue-gradient glossy',
                click:    function(win) {
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=rejectEntry&rid=RID'); ?>';
                  $.getJSON(jsonLink.replace('RID', parseInt(id)),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              }
            },
            buttonsLowPadding: true
        });
      } else if (data.rData.reviews_status == 2) {
        $.modal({
            content: modalContent,
            title: '<?php echo $lC_Language->get('modal_heading_preview_review'); ?>',
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
              '<?php echo $lC_Language->get('button_approve'); ?>': {
                classes:  'blue-gradient glossy',
                click:    function(win) {
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=approveEntry&rid=RID'); ?>';
                  $.getJSON(jsonLink.replace('RID', parseInt(id)),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              }
            },
            buttonsLowPadding: true
        });
      } else {
        $.modal({
            content: modalContent,
            title: '<?php echo $lC_Language->get('modal_heading_preview_review'); ?>',
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
              '<?php echo $lC_Language->get('button_approve'); ?>': {
                classes:  'blue-gradient glossy',
                click:    function(win) {
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=approveEntry&rid=RID'); ?>';
                  $.getJSON(jsonLink.replace('RID', parseInt(id)),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              },
              '<?php echo $lC_Language->get('button_reject'); ?>': {
                classes:  'blue-gradient glossy',
                click:    function(win) {
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=rejectEntry&rid=RID'); ?>';
                  $.getJSON(jsonLink.replace('RID', parseInt(id)),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              }

            },
            buttonsLowPadding: true
        });
      }
      $("#products_name").html(data.rData.products_name);
      $("#customers_name").html(data.rData.customers_name);
      $("#date_added").html(data.dateShort);
      $("#preview_text").html(data.rData.reviews_text);
      $("#rating_stars").html(data.ratingStars);
      $("#rating_status").html(data.rData.reviews_status_text);
    }
  );
}
</script>