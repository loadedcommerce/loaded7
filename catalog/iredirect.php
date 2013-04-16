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
// collect the post
$_SESSION['PROCESS_DATA'] = (isset($_POST) && $_POST != NULL) ? $_POST : array();
// collect the get
if (empty($_SESSION['PROCESS_DATA'])) $_SESSION['PROCESS_DATA'] = (isset($_GET) && !empty($_GET)) ? $_GET : array();
?>
<script>
 parent.location = '<?php echo lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true); ?>';
</script>
</head>
<body>
</body>
</html>