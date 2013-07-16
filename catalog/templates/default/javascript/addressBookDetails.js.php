<?php
/**
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
      if (data.single == '1') $('#uniform-zones').attr('style', 'padding:0 3 3 0;');
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
</script>