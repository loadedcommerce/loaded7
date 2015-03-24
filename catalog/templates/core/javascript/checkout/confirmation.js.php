<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: confirmation.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Language, $lC_Template, $lC_Currencies; 
?>
<script>
$( document ).ready(function() {
  var isB2B = '<?php echo (utility::isB2B() === true) ? 1 : 0; ?>';  
  if (isB2B == 1) { 
    processOTHandling();
  }
});

function sendOrderCommentsToSession(c) {
  if (c != '') {
    var jsonLink = '<?php echo lc_href_link('rpc.php', 'checkout&action=sendOrderCommentsToSession&comments=COMMENTS', 'AUTO'); ?>';   
    $.getJSON(jsonLink.replace('COMMENTS', c).split('amp;').join(''),
      function (data) {
        if (data.rpcStatus != 1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }
      }
    );
  }
}

function processOTHandling() {
  var selectedPayMethod = '<?php echo $_POST['payment_method']; ?>';
  var selectedPayTermID = '<?php echo $_POST['payment_terms']; ?>';
  var isManualPayMethod = ((selectedPayMethod == 'cod' || selectedPayMethod == 'moneyorder') ? 1 : 0);  
  var currencySymbolLeft = '<?php echo $lC_Currencies->getSessionSymbolLeft(); ?>';
  var decimals = '<?php echo (int)DECIMAL_PLACES; ?>';  
  var module = '<?php echo $lC_Template->getModule(); ?>';
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'checkout&action=getPaymentTermsSelectHtml&method=METHOD&selected=SELECTED', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('METHOD', selectedPayMethod).replace('SELECTED', selectedPayTermID).split('amp;').join(''),
    function (data) {
      if (data.rpcStatus != 1) {
        return false;
      }
      $('#payment_terms').html(data.termsSelectOptions);
    
      // adjust OT display handling
      $('.ot-handling-text').html(currencySymbolLeft + parseFloat(data.selected.handling).toFixed(decimals));
      // adjust OT display total                     
      $('.ot-total-text').html(currencySymbolLeft + parseFloat(data.selected.amount).toFixed(decimals));
      // show payment due on checkout
      if (isManualPayMethod == 1) {
        $('#ajax-msg-div').html('<input type="hidden" name="this_method" value="' + data.selected.method + '"><input type="hidden" name="this_payment" value="' + parseFloat(data.selected.payment).toFixed(decimals) + '"><input type="hidden" name="this_handling" value="' + parseFloat(data.selected.handling).toFixed(decimals) + '">');
      } else {
        $('#ajax-msg-div').html('<div class="clearfix"><div class="clearfix"><hr><span class="pull-left"><?php echo $lC_Language->get('payment_terms_due_at_checkout'); ?></span><span class="pull-right">' + currencySymbolLeft + parseFloat(data.selected.payment).toFixed(decimals)  + '</span><input type="hidden" name="this_payment" value="' + parseFloat(data.selected.payment).toFixed(decimals) + '"><input type="hidden" name="this_handling" value="' + parseFloat(data.selected.handling).toFixed(decimals) + '"><input type="hidden" name="this_method" value="' + data.selected.method + '"></div></div>');
      }      
    }
  );  
}
</script>