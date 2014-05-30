<?php
/**
  @package    catalog::admin::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: header.php v1.0 2013-08-08 datazen $
*/
?>
<!-- Title bar -->
<header role="banner" id="title-bar">
  <div class="relative">
    <a id="logoRef" style="" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT); ?>"><img id="logoImg" src="./templates/default/img/logo.png" border="0"></a>
    <?php 
    if (utility::isB2B() === true) {
      echo '<span style="vertical-align:50%;"><span class="tag orange-gradient glossy" style="padding-top:2px; font-size:1.0em;">B2B</span></span>';
    } else if (utility::isPro() === true) {
      echo '<span style="vertical-align:50%;"><span class="tag red-gradient glossy" style="padding-top:2px; font-size:1.0em;">PRO</span></span>';
    }
    ?>
  </div>
</header>
<?php
if ( $lC_MessageStack->size('header') > 0 ) {
  echo $lC_MessageStack->get('header');
}
?>