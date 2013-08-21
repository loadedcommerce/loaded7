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
</script>