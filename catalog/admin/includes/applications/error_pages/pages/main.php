<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 datazen $
*/
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Language->get('heading_title'); ?></h1>
  </hgroup>
  <div class="with-padding">
    <div class="columns">
      <div class="twelve-columns-mobile">
        <p class="align-center margin-top"><img src="./templates/default/img/no-access.png" border="0"></p>
        <h2 class="red align-center"><?php echo $lC_Language->get('ms_error_no_access'); ?></h2>
      </div>
    </div>
  </div>
</section>
<!-- End main content -->