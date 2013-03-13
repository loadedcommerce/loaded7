<?php
/**  
  $Id: iredirect.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 Loaded Commerce Team
  @license    http://loadedcommerce.com/license.html
*/
require('includes/application_top.php');
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<?php
if (isset($_POST) && $_POST != NULL) $_SESSION['PROCESS_DATA'] = $_POST;
?>
<script>
 parent.location = '<?php echo lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true); ?>';
</script>
</head>
<body>
</body>
</html>