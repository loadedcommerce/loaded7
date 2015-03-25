<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: payment.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Currencies;
?>
<script>
$( document ).ready(function() {
  var isB2B = '<?php echo (utility::isB2B() === true) ? 1 : 0; ?>';  
  if (isB2B == 1) { 
    processTermsSelect();
  }
});

var selected;

function selectRowEffect(object, buttonSelect) {
  
  $('.content-checkout-payment-methods-table tr').removeClass('module-row-selected');
  
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'module-row';
  object.className = 'module-row-selected';
  selected = object;

  // one button is not an array
  if (document.checkout_payment.payment_method[0]) {
    document.checkout_payment.payment_method[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment_method.checked=true;
  }
  
  var isB2B = '<?php echo (utility::isB2B() === true) ? 1 : 0; ?>';  
  if (isB2B == 1) { 
    processTermsSelect();
  }  
}

function toggleSecurityInfo() {
  var open = $('.security-info-text-container').is(':visible');
  if (!open) {
    $('.security-info-text-container').slideDown();
    $('#arrow').removeClass('arrow-down').addClass('arrow-up');
  } else {
    $('.security-info-text-container').slideUp();
    $('#arrow').removeClass('arrow-up').addClass('arrow-down');
  }  
}

$("input:radio[name=payment_method]").click(function() {
  var isB2B = '<?php echo (utility::isB2B() === true) ? 1 : 0; ?>';  
  if (isB2B == 1) { 
    processTermsSelect();
  }  
});

function processTermsSelect() {
  var selectedPayMethod = $("input:radio[name=payment_method]:checked").val();
  var selectedPayTermID = $('#payment_terms :selected').val();
  
  // adjust payment terms selection array  
  updatePaymentTermsSelectHtml(selectedPayMethod, selectedPayTermID);  
}

function updatePaymentTermsSelectHtml(selectedPayMethod, selectedPayTermID) {
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
      $('.ot-terms_handling-text').html(currencySymbolLeft + parseFloat(data.selected.handling).toFixed(decimals));
      // adjust OT display total                     
      $('.ot-total-text').html(currencySymbolLeft + parseFloat(data.selected.amount).toFixed(decimals));
      // show payment due on checkout
      if (isManualPayMethod == 1) {
        $('#ajax-msg-div').html('');
      } else {
        $('#ajax-msg-div').html('<div class="clearfix"><div class="clearfix"><hr><span class="pull-left"><?php echo $lC_Language->get('payment_terms_due_at_checkout'); ?></span><span class="pull-right">' + currencySymbolLeft + parseFloat(data.selected.payment).toFixed(decimals)  + '</span><input type="hidden" name="this_payment" value="' + parseFloat(data.selected.payment).toFixed(decimals) + '"><input type="hidden" name="this_handling" value="' + parseFloat(data.selected.handling).toFixed(decimals) + '"></div></div>');
      }      
    }
  );  
}
</script>