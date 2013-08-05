<?php
/**  
*  $Id: search.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('search') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('search', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/search/search.php start-->
<div class="full_page">
  <div class="content">
    <form name="advanced_search" id="advanced_search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get" onsubmit="return check_form(this);">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div id="searchInput" class="borderPadMe">   
        <p><b><?php echo $lC_Language->get('search_criteria_title'); ?></b></p>
        <?php echo lc_draw_input_field('keywords', null, 'style="width: 99%;"'); ?>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="infoContactActions" class="action_buttonbar">
        <span class="buttonLeft">
          <a onclick="javascript:window.open('<?php echo lc_href_link(FILENAME_SEARCH, 'help'); ?>', 'searchHelp', 'location=0, status=0, toolbar=0, menubar=0, scrollbars=1, width=800, height=445'); return false" style="text-decoration:none;">
            <button class="button brown_btn" type="button"><?php echo $lC_Language->get('search_help_tips'); ?></button>
          </a>
        </span>
        <span class="buttonRight"><a onclick="$('#advanced_search').submit();"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="advancedSearchCriteria" class="borderPadMe" style="min-height:245px;">
        <p><b><?php echo $lC_Language->get('advanced_search_heading'); ?></b></p>
        <div style="clear:both; height:5px;"></div>
        <div class="short-code-column one-half">
          <div class="advancedSearchFormLeft">
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
        <div class="short-code-column one-half column-last">
          <div class="advancedSearchFormRight">
            <ol>
              <li><?php echo lc_draw_pull_down_menu('category', lC_Default::getCategoriesDropdownArray()); ?></li>
              <li><?php echo lc_draw_checkbox_field('recursive'); ?></li>
              <li><?php echo lc_draw_pull_down_menu('manufacturer', lC_Default::getManufacturerDropdownArray()); ?></li>
              <li><?php echo lc_draw_input_field('pfrom'); ?></li>
              <li><?php echo lc_draw_input_field('pto'); ?></li>
              <li><?php echo lc_draw_date_pull_down_menu('datefrom', null, false, null, null, @date('Y') - $lC_Search->getMinYear(), 0); ?></li>
              <li><?php echo lc_draw_date_pull_down_menu('dateto', null, null, null, null, @date('Y') - $lC_Search->getMaxYear(), 0); ?></li>
            </ol>
          </div>
        </div>
      </div>
      <div style="clear:both;"></div>
    </div>
    <?php echo lc_draw_hidden_session_id_field(); ?>
    </form>
  </div>
</div>
<!--content/search/search.php end-->