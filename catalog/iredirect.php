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
    <form id="iredirect" name="iredirect" method="post" target="_top" action="<?php echo lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL'); ?>">
      <?php echo $postString; ?>
    </form>
    <script>
      document.iredirect.submit();
    </script>
  </body>
</html>