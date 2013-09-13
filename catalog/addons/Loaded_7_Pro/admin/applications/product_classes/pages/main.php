<?php
/**
  @package    catalog::templates
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 datazen $
*/
?>
<!-- main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  .dataColCheck { text-align: center; } 
  .dataColItem { text-align: left; } 
  .dataColAction { text-align: right; } 
  </style>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>   
          <th scope="col" class="hide-on-mobile align-left"><input onclick="toggleCheck();" id="check-all" type="checkbox" value="1" name="check-all"></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_item'); ?></th>
          <th scope="col" class="align-right"><span class="button-group compact"><a href="javascript:void(0);" style="cursor:pointer" onclick="oTable.fnReloadAjax();" class="button with-tooltip icon-redo blue" title="<?php echo $lC_Language->get('button_refresh'); ?>"></a></span><span id="actionText">&nbsp;&nbsp;<?php echo $lC_Language->get('table_heading_action'); ?></span></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3">&nbsp;</th>
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
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<!-- /main content -->