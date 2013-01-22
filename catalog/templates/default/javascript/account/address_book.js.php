<?php
/*
  $Id: address_book.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
 
  @function The lC_Default class manages default template functions
*/ 
global $lC_Language, $Qentry; 

if (isset($_GET['address_book']) && is_numeric($_GET['address_book'])) {
  ?>
  <script>
  $(document).ready(function() {
    var country = '<?php echo (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY); ?>';
    var zone = '<?php echo (isset($Qentry) ? $Qentry->value('entry_zone_id') : null); ?>'; 
    var zoneName = '<?php echo (isset($Qentry) ? $Qentry->value('entry_state') : null); ?>'; 
    if (zone == '0') zone = zoneName;
    getZonesDropdown(country, zone);  
    
    if ($.browser.mozilla) {
      alert('here');
      $('#uniform-zones select').css( "padding-top","8px" );
    }
  });

  $('#address_book').submit(function() {
    var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
    var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
    jQuery.validator.messages.required = "";
    var bValid = $("#address_book").validate({
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
      $('#address_book').submit();
    }
    return false;
  });

  function getZonesDropdown(country, zone) {
    var jsonLink = '<?php echo lc_href_link('rpc.php', 'action=getZonesDropdown&country=COUNTRY&zone=ZONE'); ?>';   
    $.getJSON(jsonLink.replace('COUNTRY', country).replace('&amp;', '&').replace('ZONE', zone).replace('&amp;', '&'),
      function (data) {
        if (data.rpcStatus != 1) {
          alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          return false;
        }
        $('#uniform-zones').html(data.zonesHtml).change();
        if (data.single == '1') {
          $('#uniform-zones').attr('style', 'padding:0 0 5px 0;');
        }
      }
    );
  }
  </script>
  <?php
}
?>