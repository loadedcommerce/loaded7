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
  $.getJSON(jsonLink.replace('COUNTRY', country).replace('&amp;', '&').replace('ZONE', zone).replace('&amp;', '&'),
    function (data) {
      if (data.rpcStatus != 1) {
        alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $('#uniform-zones').html(data.zonesHtml).change();
      if ($.browser.mozilla) $('#uniform-zones select').attr( "style", "padding-top:6px" );
      if (data.single == '1') $('#uniform-zones').attr('style', 'padding:0px 3px 3px 0px;');
      if ($.browser.mozilla) $('#uniform-zones select').each(function(){
         var valu = $(this).attr('value');
          if( $('option:selected', this).val() != ''  ) valu = $('option:selected',this).text();
         $(this)
         .css({'opacity':'0','-khtml-appearance':'none'})
         .before('<span style="-moz-user-select: none;">'+valu+'</span>' )
          .change(function(){
                    val = $('option:selected',this).text();
                    $(this).next().text(val);
                    })
     });
    }
  );
}
$("#uniform-zones").change(function(){
var selectedValue = $(this).find(":selected").val();
$("#uniform-zones").find("span:first").text(selectedValue);
});

</script>