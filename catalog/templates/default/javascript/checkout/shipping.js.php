<script>
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
  if (document.checkout_shipping.shipping_mod_sel[0]) {
    document.checkout_shipping.shipping_mod_sel[buttonSelect].checked=true;
  } else {
    document.checkout_shipping.shipping_mod_sel.checked=true;
  }
}
</script>