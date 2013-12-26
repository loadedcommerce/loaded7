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
  var defaultId = '<?php echo DEFAULT_CUSTOMERS_GROUP_ID; ?>';
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
      $.modal({
          content: '<div id="editEntry">'+
                   '  <div id="editEntryForm">'+
                   '    <form name="review" id="review" autocomplete="off" action="" method="post">'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="products_name" class="label"><?php echo $lC_Language->get('field_product'); ?></label>'+
                   '        <span id="products_name"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="customers_name" class="label"><?php echo $lC_Language->get('field_author'); ?></label>'+
                   '        <span id="customers_name"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="date_added" class="label"><?php echo $lC_Language->get('field_date_added'); ?></label>'+
                   '        <span id="date_added"></span>'+
                   '      </p>'+
                   '      <p class="inline-label">'+
                   '        <label for="preview_text" class="label"><?php echo $lC_Language->get('field_review'); ?></label>'+
                   '        <?php echo lc_draw_textarea_field('reviews_text', null, '60', '8', 'class="input"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="rating_stars" class="label"><?php echo $lC_Language->get('field_rating'); ?></label>'+
                   '        <span class="button-group"><span class="button red-gradient glossy" style="padding:0 18px"><?php echo strtoupper($lC_Language->get('rating_bad')); ?></span><span id="rating_radio"></span></span><span class="button green-gradient glossy"><?php echo strtoupper($lC_Language->get('rating_good')); ?></span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_review'); ?>',
          width: 600,
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
                var bValid = $("#review").validate({
                  rules: {
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var nvp = $("#review").serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveEntry&rid=RID&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('RID', parseInt(id)).replace('BATCH', nvp),
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
      $("#products_name").html(data.rData.products_name);
      $("#customers_name").html(data.rData.customers_name);
      $("#date_added").html(data.dateShort);
      $("#reviews_text").val(data.rData.reviews_text);
      $("#rating_radio").html(data.ratingRadio);
    }
  );
}
</script>