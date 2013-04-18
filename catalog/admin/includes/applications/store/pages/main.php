<?php
  /*
  $Id: main.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
  
typesArr = array(  
?>
<!-- main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin" style="padding-bottom:0;">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
    .dataColTitle { text-align: left; }
    .dataColValue { text-align: left; }
    .dataColAction { text-align: right; }
    .cfg-menu-selected { color: #ff9f00 !important; font-size: 1.2em; }
  </style>
  <div class="with-padding">
    <!-- main panel -->
    <div class="content-panel margin-bottom">
      <!-- menu nav panel -->
      <div class="panel-navigation silver-gradient" style=overflow:hidden;">
        <div class="panel-control"><p align="center" class="big-text small-margin-left"><strong>Type</strong></p></div>
        <div class="panel-load-target scrollable" style="height:490px">
          <div class="navigable">
            <ul class="unstyled-list open-on-panel-content">
              <?php echo lC_Store_Admin::drawMenu(); ?>
            </ul>
          </div>
        </div>
      </div>
      <!-- datatable content panel -->
      <div class="panel-content">
        <div class="panel-control">
          <p id="cfgTitle" align="center" class="big-text">Installed
          <a href="javascript://" onclick="oTable.fnReloadAjax();" class="button icon-undo float-right" style="margin-top:1px;"><?php echo $lC_Language->get('button_refresh'); ?></a></p>
        </div>
        <div class="panel-load-target scrollable" style="min-height:460px">
          <div class="large-box-shadow white-gradient with-border" style="padding:3px;">
            <table border="0" width="100%" cellspacing="0" cellpadding="0" class="simple-table responsive-table" id="dataTable">
              <thead>
                <tr>
                  <th scope="col" class="align-left"><?php //echo $lC_Language->get('table_heading_title'); ?></th>
                  <th scope="col" class="align-left"><?php //echo $lC_Language->get('table_heading_value'); ?></th>
                  <th scope="col" class="align-right"><?php //echo $lC_Language->get('table_heading_action'); ?></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- End main content -->