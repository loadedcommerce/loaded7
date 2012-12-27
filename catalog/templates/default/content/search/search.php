<?php
/*
  $Id: search.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('search') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('search', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--SEARCH SECTION STARTS-->
  <div class="full_page">
    <!--SEARCH CONTENT STARTS-->
    <div class="content">
      <form name="advanced_search" id="advanced_search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get" onsubmit="return check_form(this);">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <!--SEARCH INPUT STARTS-->
        <div id="searchInput" class="borderPadMe">   
          <p><b><?php echo $lC_Language->get('search_criteria_title'); ?></b></p>
          <?php echo lc_draw_input_field('keywords', null, 'style="width: 99%;"'); ?>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--SEARCH INPUT ENDS-->
        <!--SEARCH ACTIONS STARTS-->
        <div id="infoContactActions" class="action_buttonbar">
          <span class="buttonLeft">
            <a onclick="javascript:window.open('<?php echo lc_href_link(FILENAME_SEARCH, 'help'); ?>', 'searchHelp', 'location=0, status=0, toolbar=0, menubar=0, scrollbars=1, width=800, height=445'); return false" style="text-decoration:none;">
              <button class="button brown_btn" type="button"><?php echo $lC_Language->get('search_help_tips'); ?></button>
            </a>
          </span>
          <span class="buttonRight"><a onclick="$('#advanced_search').submit();"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--SEARCH ACTIONS ENDS-->
        <!--ADVANCED SEARCH STARTS-->
        <div id="advancedSearchCriteria" class="borderPadMe" style="min-height:245px;">
          <p><b><?php echo $lC_Language->get('advanced_search_heading'); ?></b></p>
          <div style="clear:both; height:5px;"></div>
          <!--ADVANCED SEARCH LABELS STARTS-->
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
          <!--ADVANCED SEARCH LABELS ENDS-->
          <!--ADVANCED SEARCH FIELDS STARTS-->
          <div class="short-code-column one-half column-last">
            <div class="advancedSearchFormRight">
              <ol>
                <li>
                <?php
                  $lC_CategoryTree->setSpacerString('&nbsp;', 2);                                                                      
                  $categories_array = array(array('id' => '', 'text' => $lC_Language->get('filter_all_categories')));
                  foreach ($lC_CategoryTree->buildBranchArray(0) as $category) {
                    $categories_array[] = array('id' => $category['id'],
                                                'text' => $category['title']);
                  }
                  echo lc_draw_pull_down_menu('category', $categories_array); 
                ?>
                </li>
                <li><?php echo lc_draw_checkbox_field('recursive'); ?></li>
                <li>
                <?php
                  $manufacturers_array = array(array('id' => '', 'text' => $lC_Language->get('filter_all_manufacturers')));
                  $Qmanufacturers = $lC_Database->query('select manufacturers_id, manufacturers_name from :table_manufacturers order by manufacturers_name');
                  $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
                  $Qmanufacturers->execute();
                  while ($Qmanufacturers->next()) {
                    $manufacturers_array[] = array('id' => $Qmanufacturers->valueInt('manufacturers_id'),
                                                   'text' => $Qmanufacturers->value('manufacturers_name'));
                  }
                  echo lc_draw_pull_down_menu('manufacturer', $manufacturers_array);
                ?>
                </li>
                <li><?php echo lc_draw_input_field('pfrom'); ?></li>
                <li><?php echo lc_draw_input_field('pto'); ?></li>
                <li><?php echo lc_draw_date_pull_down_menu('datefrom', null, false, null, null, @date('Y') - $lC_Search->getMinYear(), 0); ?></li>
                <li><?php echo lc_draw_date_pull_down_menu('dateto', null, null, null, null, @date('Y') - $lC_Search->getMaxYear(), 0); ?></li>
              </ol>
            </div>
          </div>
          <!--ADVANCED SEARCH FIELDS ENDS-->
        </div>
        <div style="clear:both;"></div>
        <!--ADVANCED SEARCH ENDS-->
      </div>
      <?php echo lc_draw_hidden_session_id_field(); ?>
      </form>
    </div>
  </div>
  <!--SEARCH CONTENT ENDS-->
<!--SEARCH SECTION ENDS-->