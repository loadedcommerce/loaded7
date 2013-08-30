<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: payment_address.js.php v1.0 2013-08-08 datazen $
*/
?>
?>
<script>
var selected;

$(document).ready(function() {  
  $('#payment-address-form').show();
  if (_setMediaType() == 'mobile-portrait') {
    $('#payment-address-form').text('<?php echo $lC_Language->get('text_add'); ?>'); 
  }
  $('#addressBookDetails > div').removeClass('large-padding-left').addClass('padding-left');;
  $('.small-margin-left-neg').removeClass('small-margin-left-neg').removeClass('no-margin-top').addClass('large-margin-top-neg');;
});        

$('#payment-address-form').click(function(){
 var isVisible = $('#checkoutBillingAddressDetails').is(':visible');
 var mediaType = _setMediaType();
 if (!isVisible) {
   var text = (mediaType == 'mobile-portrait') ? '<?php echo $lC_Language->get('text_hide'); ?>' : '<?php echo $lC_Language->get('hide_address_form'); ?>';
   $('#payment-address-form').html(text);
   $('#checkout_address').attr('onsubmit', 'return check_form(checkout_address);');
 } else {
   var text = (mediaType == 'mobile-portrait') ? '<?php echo $lC_Language->get('text_add'); ?>' : '<?php echo $lC_Language->get('show_address_form'); ?>';
   $('#payment-address-form').html(text);
   $('#checkout_address').removeAttr('onsubmit');
 }
 $('#checkoutBillingAddressDetails').toggle('slideUp');
});

function selectRowEffect(object, buttonSelect) {
  $('#content-checkout-address-selection-table tr').removeClass('module-row-selected');
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
  if (document.checkout_address.address[0]) {
    document.checkout_address.address[buttonSelect].checked=true;
  } else {
    document.checkout_address.address.checked=true;
  }
}
</script>