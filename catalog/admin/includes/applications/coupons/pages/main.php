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
  .dataColName {  }
  .dataColStatus { text-align: center; }
  .dataColCode {  }
  .dataColReward {  }
  .dataColLimit {  }
  .dataColRestriction {  }
  .dataColAction { text-align: right; }
  .dataTables_info { position:absolute; bottom:42px; color:#4c4c4c; }
  .selectContainer { position:absolute; bottom:29px; left:30px }
  small.tag { text-transform: none; font-size: 11px; line-height: 20px !important; }
  </style>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="hide-on-mobile align-left"><input onclick="toggleCheck();" id="check-all" type="checkbox" value="1" name="check-all"></th>
          <th scope="col"><?php echo $lC_Language->get('table_heading_name'); ?></th>
          <th scope="col"><?php echo $lC_Language->get('table_heading_status'); ?></th>
          <th scope="col"><?php echo $lC_Language->get('table_heading_code'); ?></th>
          <th scope="col"><?php echo $lC_Language->get('table_heading_reward'); ?></th>
          <th scope="col"><?php echo $lC_Language->get('table_heading_limits'); ?></th>
          <th scope="col" class="no-wrap"><?php echo $lC_Language->get('table_heading_restrictions') . lc_go_pro(); ?></th>
          <th scope="col" class="align-right">
           <span class="button-group compact" style="white-space:nowrap;">
             <a style="display:none;" href="javascript:void(0);" style="cursor:pointer" class="on-mobile button with-tooltip icon-plus-round green<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="newCoupon(); return false;'); ?>" title="<?php echo $lC_Language->get('button_new_coupon'); ?>"></a>
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
    <div class="selectContainer">
      <select <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onchange="batchDelete();"'); ?> name="selectAction" id="selectAction" class="select blue-gradient glossy<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
        <option value="0" selected="selected"><?php echo $lC_Language->get('text_with_selected'); ?>:</option>
        <option value="delete"><?php echo $lC_Language->get('text_delete'); ?></option>
      </select>
    </div>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=save')); ?>">
                <span class="button-icon green-gradient">
                  <span class="icon-plus"></span>
                </span><?php echo $lC_Language->get('button_new_coupon'); ?>
              </a>&nbsp;
              <a upsell="<?php echo $lC_Language->get('text_new_rule_upsell_desc'); ?>" class="button" href="javascript:void(0);" onclick="showUpsellSpot(this); return false;">
                <span class="button-icon green-gradient">
                  <span class="icon-plus"></span>
                </span><?php echo $lC_Language->get('button_new_rule'); ?><small class="tag red-gradient small-margin-left">Pro</small>
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
  $lC_Template->loadModal($lC_Template->getModule()); 
?>
<!-- End main content -->