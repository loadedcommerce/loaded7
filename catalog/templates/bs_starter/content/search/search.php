<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: search.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/search/search.php start-->
<div class="col-sm-12 col-lg-12">
  <form role="form" class="form-horizontal" name="advanced_search" id="advanced_search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get" onsubmit="return check_form(this);">
    <div class="row">
      <div class="col-sm-12 col-lg-12 large-margin-bottom">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <?php 
        if ( $lC_MessageStack->size('search') > 0 ) echo '<div class="message-stack-container alert alert-danger">' . $lC_MessageStack->get('search') . '</div>' . "\n"; 
        ?>      
        <div class="content-search-container">   
          <div class="form-group large-padding-left large-padding-right">
            <label class="sr-only"></label>
            <input type="text" name="keywords" value="" class="form-control" placeholder="<?php echo $lC_Language->get('search_criteria_title'); ?>">
          </div>
        </div>    
        <div class="button-set">
          <button class="pull-right btn btn-lg btn-primary" onclick="$('#advanced_search').submit();" type="button"><?php echo $lC_Language->get('button_search'); ?></button>
          <p class="help-block margin-left"><a href="javascript://" onclick="javascript:window.open('<?php echo lc_href_link(FILENAME_SEARCH, 'help'); ?>', 'searchHelp', 'location=0, status=0, toolbar=0, menubar=0, scrollbars=1, width=600, height=445'); return false"><?php echo $lC_Language->get('search_help_tips'); ?></a></p>
        </div>      
      </div>
    </div>
    <div class="row large-margin-top">  
      <div class="col-sm-12 col-lg-12">
        <h3 class="large-margin-bottom margin-top"><?php echo $lC_Language->get('advanced_search_heading'); ?></h3>
        <div class="form-group">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right"><?php echo $lC_Language->get('field_search_categories'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo lc_draw_pull_down_menu('category', lC_Bs_starter::getCategoriesDropdownArray(), null, 'class="form-control"'); ?></div>
        </div>
        <div class="form-group">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right margin-top"><?php echo $lC_Language->get('field_search_recursive'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo lc_draw_checkbox_field('recursive', null, null, 'class="form-control"'); ?></div>
        </div>
        <div class="form-group">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo $lC_Language->get('field_search_manufacturers'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo lc_draw_pull_down_menu('manufacturer', lC_Bs_starter::getManufacturerDropdownArray(), null, 'class="form-control"'); ?></div>
        </div>
        <div class="form-group">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo $lC_Language->get('field_search_price_from'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo lc_draw_input_field('pfrom', null, 'class="form-control"'); ?></div>
        </div>
        <div class="form-group">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo $lC_Language->get('field_search_price_to'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo  lc_draw_input_field('pto', null, 'class="form-control"'); ?></div>
        </div>
        <div class="form-group">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo $lC_Language->get('field_search_date_from'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo lc_draw_date_pull_down_menu('datefrom', null, false, null, null, @date('Y') - $lC_Search->getMinYear(), 0, 'class="form-control"'); ?></div>
        </div>
        <div class="form-group no-margin-bottom">
          <label for="category" class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo $lC_Language->get('field_search_date_to'); ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo lc_draw_date_pull_down_menu('dateto', null, null, null, null, @date('Y') - $lC_Search->getMaxYear(), 0, 'class="form-control"'); ?></div>
        </div>                                                
      </div>                                                                                                                                                
      <?php echo lc_draw_hidden_session_id_field(); ?>
    </div>
  </form>
  <hr>
</div>
<script>
$(document).ready(function() {
  $('.datepicker').datepicker();
});
</script>
<!--content/search/search.php end-->