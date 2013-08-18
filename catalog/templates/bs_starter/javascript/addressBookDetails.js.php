<?php
/**
  @package    catalog::templates::javascript
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: addressBookDetails.js.php v1.0 2013-08-08 datazen $
*/
global $lC_Language, $Qentry; 

?>
<script>
$(document).ready(function() {
  var country = '<?php echo (isset($Qentry) ? $Qentry->valueInt('entry_country_id') : STORE_COUNTRY); ?>';
  var zone = '<?php echo (isset($Qentry) ? $Qentry->value('entry_zone_id') : null); ?>'; 
  var zoneName = '<?php echo (isset($Qentry) ? $Qentry->value('entry_state') : null); ?>'; 
  if (zone == '0') zone = zoneName;
  getZonesDropdown(country, zone);  
});

function getZonesDropdown(country, zone) {
  var jsonLink = '<?php echo lc_href_link('rpc.php', 'action=getZonesDropdown&country=COUNTRY&zone=ZONE', 'AUTO'); ?>';   
  $.getJSON(jsonLink.replace('COUNTRY', country).replace('&amp;', '&').replace('ZONE', zone).split('amp;').join(''),
    function (data) {
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }

    }
  );
}
</script>