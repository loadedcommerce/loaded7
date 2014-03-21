<?php
/**
  @package    catalog
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Language, $Qentry; 
?>
<script>
function getZonesDropdown(country, zone) {
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'account&action=getZonesDropdown&country=COUNTRY&zone=ZONE', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('COUNTRY', country).replace('&amp;', '&').replace('ZONE', zone).replace('&amp;', '&'),
    function (data) {
      
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $('.address-book-state-container').html(data.zonesHtml);
      if (country != 223) {
        $("#postcode").attr("placeholder", "<?php echo $lC_Language->get('field_customer_post_code'); ?>");
      } else {
        $("#postcode").attr("placeholder", "<?php echo $lC_Language->get('field_customer_zip_code'); ?>");
      }
    }
  );
}
</script>