<?php
/**
  @package    catalog::templates::javascript
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Customer;
?>
<script><!--
$(document).ready(function() { 
  var allowCreateAccount = '<?php echo (defined('B2B_SETTINGS_ALLOW_SELF_REGISTER') && B2B_SETTINGS_ALLOW_SELF_REGISTER == 1) ? 1 : 0; ?>';
  var isB2B = '<?php echo (defined('ADDONS_SYSTEM_LOADED_7_B2B_STATUS') && ADDONS_SYSTEM_LOADED_7_B2B_STATUS == 1) ? 1 : 0; ?>';
  var custAccess = '<?php echo (defined('B2B_SETTINGS_GUEST_CATALOG_ACCESS') && B2B_SETTINGS_GUEST_CATALOG_ACCESS > 0) ? (int)B2B_SETTINGS_GUEST_CATALOG_ACCESS : 0; ?>';
  var isGuest = '<?php echo (($lC_Customer->isLoggedOn() === false) ? 1 : 0); ?>';
  if (isB2B == 1) {
    if (isGuest == 1) {
      if (custAccess == 33) { // view catalog
        $('.pricing-row').empty();
        $('.buy-btn-div').empty();
        $('.content-products-info-price').remove();
        $('.ships24hours').addClass('pull-left margin-bottom margin-left');
      } else if (custAccess == 66) { // see pricing
        $('.buy-btn-div').empty();
      }  
    }
    if (allowCreateAccount == 0) {
      $('.create-account-div').empty();
    }
    $('.page-results').hide();
  } 
  
  var gAccess = ('<?php echo $lC_Customer->getCustomerGroupAccess($lC_Customer->getID()); ?>').split(';');
  $(".box-categories-ul-top li a").each(function(){
    var cAccess = $(this).attr('access').split(';');
    $ok = false;
    $.each(cAccess, function(i, val) {
      if ($.inArray(val, gAccess) != -1) {
        $ok = true;
        return;  
      }
    });
    if (!$ok) $(this).closest('li').hide();
  });
});  
//--></script>