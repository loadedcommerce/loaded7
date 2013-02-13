<?php
/*
  $Id: application_bottom.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/    
$lC_Services->stopServices();
?>
<script>
$(document).ready(function() {
  var showDebug = '<?php echo $lC_Template->showDebugMessages(); ?>';
  if (showDebug) {
    var debugOutput = <?php echo (isset($_SESSION['debugStack']) && !empty($_SESSION['debugStack'])) ? $_SESSION['debugStack'] : "''" ?>;
    $('#debugInfoContainer > span').html(debugOutput);
    $('#debugInfoContainer').show();
  } else {
    $('#debugInfoContainer').hide();
  }
}); 
</script>