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
$lastRestoreDate = ( defined('DB_LAST_RESTORE') ) ? '<table><tr><td>' . $lC_Language->get('last_restoration_date') . ' ' . DB_LAST_RESTORE . '</td><td><a href="javsscript(void);" onclick="doForget();"><img border="0" src="' .  lc_icon_admin_raw('delete.png') . '" title="' . $lC_Language->get('forget_restoration_date') . '"></a></td></tr></table>' : '';
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  .dataColCheck { text-align: center; }
  .dataRowBackups { text-align: left; } 
  .dataRowDate { text-align: left; } 
  .dataRowSize { text-align: left; } 
  .dataRowAction { text-align: right; }
  .dataTables_info { position:absolute; bottom: 42px; color:#4c4c4c; }
  .selectContainer { position:absolute; bottom:29px; left:30px }
  </style>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="hide-on-mobile align-left"><input onclick="toggleCheck();" id="check-all" type="checkbox" value="1" name="check-all"></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_backups'); ?></th>
          <th scope="col" class="align-left hide-on-mobile-portrait"><?php echo $lC_Language->get('table_heading_date'); ?></th>
          <th scope="col" class="align-left hide-on-mobile"><?php echo $lC_Language->get('table_heading_file_size'); ?></th>
          <th scope="col" class="align-right">
            <span class="button-group compact" style="white-space:nowrap;">
              <a style="display:none;" href="javsscript(void);" style="cursor:pointer" class="on-mobile button with-tooltip icon-outbox green<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="doBackup(); return false;'); ?>" title="<?php echo $lC_Language->get('button_backup'); ?>"></a>
              <a style="display:none;" href="javsscript(void);" style="cursor:pointer" class="on-mobile button with-tooltip icon-inbox blue<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? '#' : 'javascript://" onclick="restoreLocal(); return false;'); ?>" title="<?php echo $lC_Language->get('button_restore'); ?>"></a>
              <a href="javsscript(void);" style="cursor:pointer" onclick="oTable.fnReloadAjax();" class="button with-tooltip icon-redo blue" title="<?php echo $lC_Language->get('button_refresh'); ?>"></a>
            </span>
            <span id="actionText">&nbsp;&nbsp;<?php echo $lC_Language->get('table_heading_action'); ?></span>
          </th>  
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="5">&nbsp;</th>
        </tr>
      </tfoot>
    </table>
    <div class="selectContainer">
      <select <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onchange="batchDelete();"'); ?> name="selectAction" id="selectAction" class="select blue-gradient glossy<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
        <option value="0" selected="selected">With Selected</option>
        <option value="delete">Delete</option>
      </select>
    </div>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="doBackup(); return false;'); ?>">
                <span class="button-icon blue-gradient">
                  <span class="icon-outbox"></span>
                </span><?php echo $lC_Language->get('button_backup'); ?>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? '#' : 'javascript://" onclick="restoreLocal(); return false;'); ?>">
                <span class="button-icon green-gradient">
                  <span class="icon-inbox"></span>
                </span><?php echo $lC_Language->get('button_restore'); ?>
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