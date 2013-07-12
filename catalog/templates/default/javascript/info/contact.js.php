<?php
/**
  $Id: info_contact.js.php v1.0 2013-02-08 wa4u $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Default class manages default template functions
*/ 
?>
<script>
function validateForm() {
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
  jQuery.validator.messages.required = "";
  var bValid = $("#contact").validate({
    rules: {
      name: { minlength: fnameMin, required: true },
      inquiry: { minlength: 10, required: true },
      email: { minlength: emailMin, email: true, required: true },
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
    $('#contact').submit();
  }
  return false;
}
</script>