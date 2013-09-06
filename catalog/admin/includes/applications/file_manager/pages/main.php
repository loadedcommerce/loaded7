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
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  .dataColFiles { text-align: left; } 
  .dataColSize { text-align: left; } 
  .dataColPerms { text-align: left; } 
  .dataColUser { text-align: left; } 
  .dataColGroup { text-align: left; } 
  .dataColWrite { text-align: center; } 
  .dataColLast { text-align: left; }  
  .dataColAction { text-align: right; }
  </style>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_files'); ?></th>
          <th scope="col" class="align-left hide-on-mobile-portrait"><?php echo $lC_Language->get('table_heading_size'); ?></th>
          <th scope="col" class="align-left hide-on-mobile-portrait"><?php echo $lC_Language->get('table_heading_permissions'); ?></th>
          <th scope="col" class="align-left hide-on-tablet"><?php echo $lC_Language->get('table_heading_user'); ?></th>
          <th scope="col" class="align-left hide-on-tablet"><?php echo $lC_Language->get('table_heading_group'); ?></th>
          <th scope="col" class="align-left hide-on-mobile-portrait"><?php echo $lC_Language->get('table_heading_writable'); ?></th>
          <th scope="col" class="align-left hide-on-tablet"><?php echo $lC_Language->get('table_heading_date_last_modified'); ?></th>
          <th scope="col" class="align-right">
            <span class="button-group compact" style="white-space:nowrap;">
              <a style="display:none;" href="javascript:void(0);" style="cursor:pointer" class="on-mobile button with-tooltip icon-cloud-upload blue<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="uploadFile(); return false;'); ?>" title="<?php echo $lC_Language->get('button_upload'); ?>"></a>
              <a style="display:none;" href="javascript:void(0);" style="cursor:pointer" class="on-mobile button with-tooltip icon-page-list green<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newFile(); return false;'); ?>" title="<?php echo $lC_Language->get('button_new_file'); ?>"></a>
              <a style="display:none;" href="javascript:void(0);" style="cursor:pointer" class="on-mobile button with-tooltip icon-folder green<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newFolder(); return false;'); ?>" title="<?php echo $lC_Language->get('button_new_folder'); ?>"></a>
              <a href="javascript:void(0);" style="cursor:pointer" onclick="oTable.fnReloadAjax();" class="button with-tooltip icon-redo blue" title="<?php echo $lC_Language->get('button_refresh'); ?>"></a>
            </span>
            <span id="actionText">&nbsp;&nbsp;<?php echo $lC_Language->get('table_heading_action'); ?></span>
          </th>       
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="8">&nbsp;</th>
        </tr>
      </tfoot>
    </table>
    </div>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="uploadFile(); return false;'); ?>">
                <span class="button-icon blue-gradient">
                  <span class="icon-inbox"></span>
                </span><?php echo $lC_Language->get('button_upload'); ?>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newFile(); return false;'); ?>">
                <span class="button-icon green-gradient">
                  <span class="icon-page-list"></span>
                </span><?php echo $lC_Language->get('button_new_file'); ?>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newFolder(); return false;'); ?>">
                <span class="button-icon green-gradient">
                  <span class="icon-folder"></span>
                </span><?php echo $lC_Language->get('button_new_folder'); ?>
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo $lC_Template->getPageTitle(); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php 
  if (isset($_SESSION['error'])) unset($_SESSION['error']);
  if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']); 
  $lC_Template->loadModal($lC_Template->getModule()); 
?>
<!-- End main content -->