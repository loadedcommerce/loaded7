<?php
/*
  $Id: header.php v1.0 2012-08-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!-- Title bar -->
<header role="banner" id="title-bar">
  <div class="relative">
    <a id="logoRef" style="" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT); ?>"><img id="logoImg" src="./templates/default/img/logo.png" border="0"></a>
    <!-- VQMOD1 -->
  </div>
</header>
<?php
if ( $lC_MessageStack->size('header') > 0 ) {
  echo $lC_MessageStack->get('header');
}
?>