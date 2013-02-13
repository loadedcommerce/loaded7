<?php
/*
  $Id: account_edit.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
*/ 
?>
<!-- Account Edit Validations -->
<script>
$('#account_edit').submit(function() {
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
  jQuery.validator.messages.required = "";
  var bValid = $("#account_edit").validate({
    rules: {
      firstname: { minlength: fnameMin, required: true },
      lastname: { minlength: lnameMin, required: true },
      email_address: { minlength: emailMin, email: true, required: true },
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
    $('#account_edit').submit();
  }
  return false;
});

  // <![CDATA[       
  var opts = {     
    formElements:{"dob":"m-sl-d-sl-Y"},                  
    cursorDate:"19700101"                  
  };        
  datePickerController.createDatePicker(opts);
  // ]]>
</script>
<!-- Account Edit Validations end -->