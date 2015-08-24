<?php
/**
  @package    catalog
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: iredirect.php v1.0 2013-08-08 datazen $
*/
require('includes/application_top.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <?php
      // collect the post
      $data = (isset($_POST) && $_POST != NULL) ? $_POST : array();
      // collect the get
      if (empty($data)) $data = (isset($_GET) && !empty($_GET)) ? $_GET : array();
      $postString = '';
      if (is_array($data) && empty($data) === false) {
        foreach ($data as $key => $value) {
          $postString .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';    
        }
      }  
    ?>
  </head>
  <body>    
    <form id="iredirect" name="iredirect" method="post" target="_top" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', null, null, true); ?>">
      <?php echo $postString; ?>
    </form>
    <script>
      document.iredirect.submit();
    </script>
  </body>
</html>