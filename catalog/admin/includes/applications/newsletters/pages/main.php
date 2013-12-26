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
  .dataColCheck { text-align: center; }
  .dataColNewsletters { text-align: left; } 
  .dataColSize { text-align: left; } 
  .dataColModule { text-align: left; } 
  .dataColSent { text-align: center; } 
  .dataColAction { text-align: right; } 
  .dataTables_info { position:absolute; bottom: 42px; color:#4c4c4c; }
  .selectContainer { position:absolute; bottom:29px; left:30px }
  </style>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="hide-on-mobile align-left"><input onclick="toggleCheck();" id="check-all" type="checkbox" value="1" name="check-all"></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_newsletters'); ?></th>
          <th scope="col" class="align-left hide-on-mobile"><?php echo $lC_Language->get('table_heading_size'); ?></th>
          <th scope="col" class="align-left hide-on-mobile-portrait"><?php echo $lC_Language->get('table_heading_module'); ?></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_sent'); ?></th>
          <th scope="col" class="align-right">
           <span class="button-group compact" style="white-space:nowrap;">
             <a style="display:none;" href="javascript:void(0);" style="cursor:pointer" class="on-mobile button with-tooltip icon-plus-round green<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newNewsletter(); return false;'); ?>" title="<?php echo $lC_Language->get('button_new_newsletter'); ?>"></a>
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
          <th colspan="6">&nbsp;</th>
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
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newNewsletter(); return false;'); ?>">
                <span class="button-icon green-gradient">
                  <span class="icon-plus"></span>
                </span><?php echo $lC_Language->get('button_new_newsletter'); ?>
              </a>&nbsp;
            </p>
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