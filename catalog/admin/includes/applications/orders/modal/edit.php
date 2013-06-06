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
#editOrder { padding-bottom:20px; }
#statusHistoryTable TD { padding: 10px 10px 0 0; }
#partsInfoTable TD { padding: 10px 0 0 0; }
</style>
<script>
function editOrder(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getOrderInfo&oid=OID'); ?>'  
  $.getJSON(jsonLink.replace('OID', parseInt(id)),
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
          content: '<div id="editContent">'+
                   '  <form name="updateOrder" id="updateOrder" action="#" method="post">'+
                   '    <div class="standard-tabs">'+
                   '      <ul class="tabs">'+
                   '        <li><?php echo lc_link_object('#section_summary_content', $lC_Language->get('section_summary')); ?></li>'+
                   '        <li><?php echo lc_link_object('#section_products_content', $lC_Language->get('section_products')); ?></li>'+
                   '        <li><?php echo lc_link_object('#section_transaction_history_content', $lC_Language->get('section_transaction_history')); ?></li>'+
                   '        <li class="active"><?php echo lc_link_object('#section_status_history_content', $lC_Language->get('section_status_history')); ?></li>'+
                   '      </ul>'+
                   '      <div class="tabs-content">'+
                   '        <div id="section_summary_content" class="with-padding">'+
                   '          <table width="100%" border="0">'+
                   '            <tr>'+
                   '              <td width="33%" valign="top">'+
                   '                <fieldset style="border: 0; height: 100%;">'+
                   '                  <legend style="margin-bottom: 10px; margin-left: -10px; font-weight: bold;"><span class="icon-user icon-blue"><?php echo $lC_Language->get('subsection_customer'); ?></span></legend>'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block1aAddress"></span></span>'+
                   '                  <p style="margin-left:17px;"><span class="icon-phone icon-blue"><span id="block1aPhone"></span></span><br /><span class="icon-pencil icon-red"><span id="block1aEmail"></span></span></p>'+
                   '                </fieldset>'+
                   '              </td>'+
                   '              <td width="33%" valign="top">'+
                   '                <fieldset style="border: 0; height: 100%;">'+
                   '                  <legend style="margin-bottom: 10px; margin-left: -10px; font-weight: bold;"><span class="icon-card icon-red"><?php echo $lC_Language->get('subsection_shipping_address'); ?></span></legend>'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block1bAddress"></span></span>'+
                   '                </fieldset>'+
                   '              </td>'+
                   '              <td width="33%" valign="top">'+
                   '                <fieldset style="border: 0; height: 100%;">'+
                   '                  <legend style="margin-bottom: 10px; margin-left: -10px; font-weight: bold;"><span class="icon-card icon-blue"><?php echo $lC_Language->get('subsection_billing_address'); ?></span></legend>'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block1cAddress"></span></span>'+
                   '                </fieldset>'+
                   '              </td>'+
                   '            </tr>'+
                   '            <tr><td colspan="3">&nbsp;</td></tr>'+
                   '            <tr>'+
                   '              <td width="33%" valign="top">'+
                   '                <fieldset style="border: 0; height: 100%;">'+
                   '                  <legend style="margin-bottom: 10px; margin-left: -10px; font-weight: bold;"><span class="icon-price-tag icon-orange"><?php echo $lC_Language->get('subsection_payment_method'); ?></span></legend>'+
                   '                  <span style="margin-left:15px;" id="block2aMethod"></span>'+
                   '                </fieldset>'+
                   '              </td>'+
                   '              <td width="33%" valign="top">'+
                   '                <fieldset style="border: 0; height: 100%;">'+
                   '                  <legend style="margin-bottom: 10px; margin-left: -10px; font-weight: bold;"><span class="icon-clock icon-blue"><?php echo $lC_Language->get('subsection_status'); ?></span></legend>'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block2bStatus"></span></span><br /><br />'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block2bComments"></span></span>'+
                   '                </fieldset>'+
                   '              </td>'+
                   '              <td width="33%" valign="top">'+
                   '                <fieldset style="border: 0; height: 100%;">'+
                   '                  <legend style="margin-bottom: 10px; margin-left: -10px; font-weight: bold;"><span class="icon-bag icon-green"><?php echo $lC_Language->get('subsection_total'); ?></span></legend>'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block2cTotal"></span></span><br /><br />'+
                   '                  <span style="display:inline-block; margin-left:15px;"><span id="block2cProducts"></span></span>'+
                   '                </fieldset>'+
                   '              </td>'+
                   '            </tr>'+
                   '          </table>'+
                   '        </div>'+
                   '        <div id="section_products_content" class="with-padding">'+
                   '          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable" id="partsInfoTable">'+
                   '            <thead>'+
                   '              <tr>'+
                   '                <th align="left" colspan="2"><?php echo $lC_Language->get('table_heading_products'); ?></th>'+
                   '                <th align="left"><?php echo $lC_Language->get('table_heading_product_model'); ?></th>'+
                   '                <th align="right"><?php echo $lC_Language->get('table_heading_tax'); ?></th>'+
                   '                <th align="right"><?php echo $lC_Language->get('table_heading_price_net'); ?></th>'+
                   '                <th align="right"><?php echo $lC_Language->get('table_heading_price_gross'); ?></th>'+
                   '                <th align="right"><?php echo $lC_Language->get('table_heading_total_net'); ?></th>'+
                   '                <th align="right"><?php echo $lC_Language->get('table_heading_total_gross'); ?></th>'+
                   '              </tr>'+
                   '            </thead>'+
                   '            <tbody>'+
                   '            </tbody>'+
                   '          </table>'+
                   '          <table border="0" width="100%" cellspacing="0" cellpadding="2" id="orderTotalInfoTable">'+
                   '            <tbody>'+
                   '            </tbody>'+
                   '          </table>'+
                   '        </div>'+
                   '        <div id="section_transaction_history_content"><label for="section_transaction_history_content"></label>'+
                   '          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable" id="transactionInfoTable">'+
                   '            <thead>'+
                   '              <tr>'+
                   '                <th align="left" width="130"><?php echo $lC_Language->get('table_heading_date_added'); ?></th>'+
                   '                <th align="left" width="50"><?php echo $lC_Language->get('table_heading_status'); ?></th>'+
                   '                <th align="left" width="20">&nbsp;</th>'+
                   '                <th align="left"><?php echo $lC_Language->get('table_heading_comments'); ?></th>'+
                   '              </tr>'+
                   '            </thead>'+
                   '            <tbody>'+
                   '            </tbody>'+
                   '          </table>'+
                   '          <span id="transactionActionContainer" style="display:none;">'+
                   '            <table border="0" width="100%" cellspacing="0" cellpadding="2" id="transactionActionTable">'+
                   '              <tr>'+
                   '                <td>'+
                   '                  <p><?php echo $lC_Language->get('field_post_transaction_actions') . ' '. lc_draw_pull_down_menu('transaction', null, null, 'id="transaction"') . '&nbsp;<input onclick="executePostTransaction(); return false;" type="submit" value="' . $lC_Language->get('button_execute') . '" class="operationButton" />'; ?></p>'+
                   '                </td>'+
                   '              </tr>'+
                   '            </table>'+
                   '          </span>'+
                   '        </div>'+
                   '        <div id="section_status_history_content" class="with-padding">'+
                   '          <table border="0" width="100%" cellspacing="0" cellpadding="0" class="dataTable" id="orderStatusTableData">'+
                   '            <thead>'+
                   '              <tr>'+
                   '                <th align="left" width="150px"><?php echo $lC_Language->get('table_heading_date_added'); ?></th>'+
                   '                <th align="left" width="125px"><?php echo $lC_Language->get('table_heading_status'); ?></th>'+
                   '                <th align="left"><?php echo $lC_Language->get('table_heading_comments'); ?></th>'+
                   '                <th align="center" width="130px"><?php echo $lC_Language->get('table_heading_customer_notified'); ?></th>'+
                   '              </tr>'+
                   '            </thead>'+
                   '            <tbody>'+
                   '            </tbody>'+
                   '          </table>'+
                   '          <br />'+
                   '          <table border="0" width="100%" cellspacing="0" cellpadding="2" id="statusHistoryTable">'+
                   '            <tr>'+
                   '              <td><?php echo $lC_Language->get('field_status'); ?></td>'+
                   '              <td><?php echo lc_draw_pull_down_menu('status', null, null, 'class="select" style="width: 73%" id="orderStatus"'); ?></td>'+
                   '            </tr>'+
                   '            <tr>'+
                   '              <td valign="top" width="30%"><?php echo $lC_Language->get('field_add_comment'); ?></td>'+
                   '              <td width="70%"><?php echo lc_draw_textarea_field('comment', null, null, null, 'class="input" style="width: 100%"'); ?></td>'+
                   '            </tr>'+
                   '            <tr>'+
                   '              <td><?php echo $lC_Language->get('field_notify_customer'); ?></td>'+
                   '              <td><?php echo lc_draw_checkbox_field('notify_customer', null, true); ?></td>'+
                   '            </tr>'+
                   '              <td><?php echo $lC_Language->get('field_notify_customer_with_comments'); ?></td>'+
                   '              <td><?php echo lc_draw_checkbox_field('append_comment', null, true); ?></td>'+
                   '            </tr>'+
                   '            <tr>'+
                   '              <td colspan="2" align="right"><input type="hidden" name="oid" id="order_id" value=""><input class="button" onclick="updateOrderStatus(); return false;" type="submit" value="<?php echo $lC_Language->get('button_update'); ?>" class="operationButton" /></td>'+
                   '            </tr>'+
                   '          </table>'+
                   '        </div>'+
                   '      </div>'+
                   '    </div>'+
                   '  </form>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_order_status'); ?>',
          width: 600,
          scrolling: false,
          actions: {
            'Close' : {
              color: 'red',
              click: function(win) { win.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_orders_invoice'); ?>': {
              classes:  'glossy',
              click:    function(win) { 
                url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders&oid=OID&action=invoice'); ?>';
                window.open(url.replace('OID', id));                
                win.closeModal(); 
              }
            },
            '<?php echo $lC_Language->get('button_orders_packaging_slip'); ?>': {
              classes:  'glossy',
              click:    function(win) { 
                url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'orders&oid=OID&action=packaging_slip'); ?>';
                window.open(url.replace('OID', id));
                win.closeModal(); 
              }
            },                        
            '<?php echo $lC_Language->get('button_close'); ?>': {
              classes:  'glossy',
              click:    function(win) { win.closeModal(); }
            }
          },
          buttonsLowPadding: true
      });
      $("#editContentMessage").html(id);
      $("#block1aAddress").html(data.customerAddress);
      $("#block1aPhone").html(data.orderTelephone);
      $("#block1aEmail").html(data.orderEmail);
      $("#block1bAddress").html(data.deliveryAddress);
      $("#block1cAddress").html(data.billingAddress);
      $("#block2aMethod").html(data.paymentMethod);
      $("#block2bStatus").html(data.orderStatus);
      $("#block2bComments").html(data.orderComments);
      $("#block2cTotal").html(data.orderTotal);
      $("#block2cProducts").html(data.numberProducts);
      $("#partsInfoTable > tbody").html(data.orderProducts);
      $("#orderTotalInfoTable > tbody").html(data.orderTotals);
      $("#transactionInfoTable > tbody").html(data.transactionHistory);
      $("#orderStatusTableData > tbody").html(data.orderStatusHistory);
      if (typeof data.transactionActions != "undefined") {
        $.each(data.transactionActions, function(val, text) {
          $("#transaction").append(
            $("<option></option>").val(val).html(text)
          );
        });
        $("#transactionActionContainer").show();
      }
      $("#order_id").val(id);
      $("#orderStatus").empty();
      $.each(data.ordersStatusArray, function(val, text) {
        var selected = (data.orderStatusID == val) ? 'selected="selected"' : '';
        if(data.orderStatusID == val) {
          $("#orderStatus").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#orderStatus").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("[name=comment]").val(""); 
    }
  );
}
</script>