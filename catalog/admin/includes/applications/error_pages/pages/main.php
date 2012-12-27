<?php
/*
  $Id: main.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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