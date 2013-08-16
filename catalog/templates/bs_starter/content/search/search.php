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
if ($lC_MessageStack->size('search') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('search', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/search/search.php start-->
<div class="row-fluid">
  <form name="advanced_search" id="advanced_search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get" onsubmit="return check_form(this);">
    <div class="span12 large-margin-bottom">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <?php 
      if ( $lC_MessageStack->size('search') > 0 ) echo '<div class="message-stack-container alert-error">' . $lC_MessageStack->get('search') . '</div>' . "\n"; 
      ?>      
      <div class="content-search-container">   
        <div class="strong"><?php echo $lC_Language->get('search_criteria_title'); ?></div>
        <div><?php echo lc_draw_input_field('keywords', null, 'style="width: 99%;"'); ?></div>
      </div>    
      <div class="button-set">
        <button class="pull-right btn btn-large btn-success" onclick="$('#advanced_search').submit();" type="button"><?php echo $lC_Language->get('button_search'); ?></button>
        <button class="pull-left btn btn-large btn-info" onclick="javascript:window.open('<?php echo lc_href_link(FILENAME_SEARCH, 'help'); ?>', 'searchHelp', 'location=0, status=0, toolbar=0, menubar=0, scrollbars=1, width=800, height=445'); return false" type="button"><?php echo $lC_Language->get('search_help_tips'); ?></button>
      </div>      
    </div>
    
    <div class="span12">
      <div class="strong"><?php echo $lC_Language->get('advanced_search_heading'); ?></div>
      <div class="row">
        <div class="span4">
          <div class="well">
            <ol>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_categories'), 'category'); ?></li>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_recursive'), 'subs'); ?></li>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_manufacturers'), 'manufacturer'); ?></li>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_price_from'), 'pfrom'); ?></li>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_price_to'), 'pto'); ?></li>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_date_from'), 'datefrom'); ?></li>
              <li><?php echo lc_draw_label($lC_Language->get('field_search_date_to'), 'dateto'); ?></li>
            </ol>
          </div>
        </div>
           
        <div class="span8">
          <ol>
            <li><?php echo lc_draw_pull_down_menu('category', lC_Bs_starter::getCategoriesDropdownArray()); ?></li>
            <li><?php echo lc_draw_checkbox_field('recursive'); ?></li>
            <li><?php echo lc_draw_pull_down_menu('manufacturer', lC_Bs_starter::getManufacturerDropdownArray()); ?></li>
            <li><?php echo lc_draw_input_field('pfrom'); ?></li>
            <li><?php echo lc_draw_input_field('pto'); ?></li>
            <li><?php echo lc_draw_date_pull_down_menu('datefrom', null, false, null, null, @date('Y') - $lC_Search->getMinYear(), 0); ?></li>
            <li><?php echo lc_draw_date_pull_down_menu('dateto', null, null, null, null, @date('Y') - $lC_Search->getMaxYear(), 0); ?></li>
          </ol>      
        </div>                                                                                                                                                
      </div>                                                                                                                                                
    </div>
    <?php echo lc_draw_hidden_session_id_field(); ?>
  </form>    
</div>
<!--content/search/search.php end-->