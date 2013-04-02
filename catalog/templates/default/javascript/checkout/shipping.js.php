<?php
/*
  $Id: shipping.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

*/
?>
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

<?php
global $lC_Customer;
 if ($lC_Customer->hasDefaultAddress() == false) {
?>
  jQuery(window).load (function () { 
      $('#checkoutShippingAddressDetails').show();
      $('#shipping_address_form').hide();
  });
<?php
}
?>
$('#checkout_address').submit(function() {
  // validate if form is visible
  if($("#checkoutShippingAddressDetails").is(':visible')) { 
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  jQuery.validator.messages.required = "";
  var bValid = $("#checkout_address").validate({
    rules: {
      firstname: { minlength: fnameMin, required: true },
      lastname: { minlength: lnameMin, required: true },
      street_address: { required: true },
      city: { required: true },
    },
    invalidHandler: function(e, validator) {
      var errors = validator.numberOfInvalids();
      if (errors) {
        $("#errDiv").show().delay(5000).fadeOut('slow');
      } else {
        $("#errDiv").hide();
      }
      return false;
    }
  }).form();

  if (bValid) {      
    $('#checkout_address').submit();
  }
  return false;
  }
}); 
</script>