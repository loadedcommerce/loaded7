<script>
$('#paymentSelect tr').click(function() {
  $(this).find('td input:radio').prop('checked', true);
});

var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment_method[0]) {
    document.checkout_payment.payment_method[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment_method.checked=true;
  }
}
</script>