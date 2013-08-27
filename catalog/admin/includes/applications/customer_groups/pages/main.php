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
  .dataColGroup { text-align: left; }
  .dataColBase { text-align: right; }
  .dataColAction { text-align: right; }
  </style>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_customer_groups'); ?></th>
          <th scope="col" class="align-right"><?php echo $lC_Language->get('table_heading_baseline_discount'); ?></th>
          <th scope="col" class="align-right">
           <span class="button-group compact" style="white-space:nowrap;">
             <a style="display:none;" href="javsscript(void);" style="cursor:pointer" class="on-mobile button with-tooltip icon-plus-round green<?php echo (((int)$_SESSION['admin']['access']['definitions'] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access']['definitions'] < 2) ? '#' : 'javascript://" onclick="newGroup(); return false;'); ?>" title="<?php echo $lC_Language->get('button_new_group'); ?>"></a>
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
          <th colspan="4">&nbsp;</th>
        </tr>
      </tfoot>
    </table>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button<?php echo (((int)$_SESSION['admin']['access']['definitions'] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access']['definitions'] < 2) ? '#' : 'javascript://" onclick="newGroup(); return false;'); ?>">
                <span class="button-icon green-gradient">
                  <span class="icon-plus"></span>
                </span><?php echo $lC_Language->get('button_new_group'); ?>
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
$lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- End main content -->