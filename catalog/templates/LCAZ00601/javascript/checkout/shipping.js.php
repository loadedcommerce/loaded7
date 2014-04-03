<?php
/**  
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: shipping.js.php v1.0 2013-08-08 datazen $
*/ 
?>
<script>
var selected;

function selectRowEffect(object, buttonSelect) {
  
  $('.content-shipping-methods-table tr').removeClass('module-row-selected');

  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('default-selected');
    } else {
      selected = document.all['default-selected'];
    }
  }

  if (selected) selected.className = 'module-row';
  object.className = 'module-row-selected';
  selected = object;

  // one button is not an array
  if (document.checkout_shipping.shipping_mod_sel[0]) {
    document.checkout_shipping.shipping_mod_sel[buttonSelect].checked=true;
  } else {
    document.checkout_shipping.shipping_mod_sel.checked=true;
  }
}
</script>