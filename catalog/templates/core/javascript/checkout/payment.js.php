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
?>
<script>
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

function processTermsSelect(val) {
  alert(val);
}
</script>