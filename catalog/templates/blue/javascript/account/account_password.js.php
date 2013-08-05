<?php
/*
  $Id: account_password.js.php v1.0 2013-02-19 wa4u $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
*/ 
?>
<!-- Account Password Validations -->
<script>
function validateForm() {
  var passwdLength = '<?php echo ACCOUNT_PASSWORD; ?>';

  jQuery.validator.messages.required = "";
  var bValid = $("#account_password").validate({
    rules: {
      password_current: { minlength: passwdLength, required: true },
      password_new: { minlength: passwdLength, required: true },
      password_confirmation: { equalTo: "#password_new" },
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
    $('#account_password').submit();
  }
  return false;
}
</script>
<!-- Account Password Validations end -->