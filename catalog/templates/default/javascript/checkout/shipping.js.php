<script>
  $('#shippingSelect tr').click(function() {
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
    if (document.checkout_address.shipping[0]) {
      document.checkout_address.shipping[buttonSelect].checked=true;
    } else {
      document.checkout_address.shipping.checked=true;
    }
  }
</script>