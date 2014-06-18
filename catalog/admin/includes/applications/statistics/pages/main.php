<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 datazen $
*/
  require_once($lC_Vqmod->modCheck('includes/applications/orders/classes/orders.php'));
  require_once($lC_Vqmod->modCheck('includes/applications/manufacturers/classes/manufacturers.php'));

  $note = '<div class="back-color margin-top"><span class="glyphicon glyphicon-exclamation-sign padding-align"><br/>NOTE</span>'.
   $lC_Language->get('text1').'<span class="upsellinfo" upselltitle="'.$lC_Language->get('text_class_upsell_title').'" upselldesc="'.$lC_Language->get('text_additional_images_upsell_desc') . '"></span>' .lc_go_pro().$lC_Language->get('text2').'<span class="upsellinfo" upselltitle="'.
   $lC_Language->get('text_class_upsell_title').'"upselldesc="'. $lC_Language->get('text_class_upsell_desc').'"></span>'.lc_go_pro().$lC_Language->get('text3').'</div><br>';

   $breakout = '<span class="breakout-wrapper">
                 <span class="anthracite with-small-padding no-wrap" name="breakout_by" id="breakout_by">' . $lC_Language->get("text_breakout_by") . '</span>
                 <label class="no-wrap">
                   <input type="radio" class="radio" id="breakout" name="breakout" value = "class">
                   <span class="anthracite with-small-padding space">' . $lC_Language->get("text_class") . '</span>
                   ' .lc_go_pro(). '
                 </label>
                 <label class="no-wrap">
                   <input type="radio" class="radio" id="breakout" name="breakout" value="supplier">
                   <span class="anthracite with-small-padding">' . $lC_Language->get("text_supplier") . '</span>
                   <span class="upsellinfo" upselltitle="' . $lC_Language->get('text_class_upsell_title') . '" upselldesc="' . $lC_Language->get('text_class_upsell_desc') . '"></span>
                   ' . lc_go_pro() . '
                 </label>
                 <label class="no-wrap">
                   <input type="radio" class="radio" id="breakout" name="breakout" value="category" checked>
                   <span class="anthracite with-small-padding">' . $lC_Language->get("text_category") . '</span>
                 </label>
                 <label class="no-wrap">
                   <input type="radio" class="radio" id="breakout" name="breakout" value="product_sku">
                   <span class="anthracite with-small-padding">' . $lC_Language->get("text_product/SKU") . '</span>
                 </label>
                 <label class="no-wrap">
                   <input type="radio" class="radio" id="breakout" name="breakout" value="manufacturers">
                   <span class="anthracite with-small-padding">' . $lC_Language->get("text_manufacturers") . '</span>
                 </label>
               </span>';

  function get_dropdown_list_array($type) {
    global $lC_Language;

    $list_arr = array();

    switch($type) {
      case 'manufacturer':
        $list_str = $lC_Language->get('text_select_manufacturers');
        $list_arr = lC_Manufacturers_Admin::getManufacturersArray();
        break;
      case 'supplier':
        $list_str = $lC_Language->get('text_select_supplier');
        //$list_arr = lC_Orders_Admin::getOrderStatusArray();
        break;
      case 'order_status':
        //$list_str = $lC_Language->get('text_statuses');
        $list_arr = lC_Orders_Admin::getOrderStatusArray();
        break;
    }

    $i = 0;
    if($list_str != '') {
      $i = 1;
      $return_arr[0] = array('id' => '',
                           'text' => $list_str
                           );
    }

    if(is_array($list_arr) &&  count($list_arr) > 0) {
      foreach($list_arr as $k => $arr) {
        $return_arr[$k+$i] = $arr;
      }
    }
    return $return_arr;
  }
  
   /* $arr_time_span = array(
                         array('id' => 'daily',
                               'text' => $lC_Language->get('text_daily')),
                         array('id' => 'monthly',
                               'text' => $lC_Language->get('text_monthly')),
                         array('id' => 'quaterly',
                               'text' => $lC_Language->get('text_quarterly')),
                         array('id' => 'annually',
                               'text' => $lC_Language->get('text_annually'))
                         );*/
    $arr_summary = array(
                       array('id' => 'summary',
                             'text' => $lC_Language->get('text_summary')),
                       array('id' => 'detailed',
                             'text' => $lC_Language->get('text_detailed')),
                       array('id' => 'detailed_amounts',
                             'text' => $lC_Language->get('text_detailed_amounts'))
                       );

   
    $manufacturer_arr = get_dropdown_list_array('manufacturer');
    $supplier_arr     = get_dropdown_list_array('supplier');
    $order_status_arr = get_dropdown_list_array('order_status');

    $manufacturer_dropdown = lc_draw_pull_down_menu('manufacturer', $manufacturer_arr, null, 'class="input with-small-padding small-margin-bottom"') . '&nbsp;';
    $supplier_dropdown     = lc_draw_pull_down_menu('supplier', $supplier_arr, null, 'class="input with-small-padding small-margin-bottom"') . '&nbsp;';
    $order_status_dropdown = lc_draw_pull_down_menu('order_status', $order_status_arr, null, 'class="input with-small-padding small-margin-bottom"') . '&nbsp;';          
    //$time_span_dropdown    = lc_draw_pull_down_menu('time_span', $arr_time_span, null, 'class="green-gradient select expandable-list small-margin-bottom"') . '&nbsp;';
    $summary_dropdown      = lc_draw_pull_down_menu('summary', $arr_summary, null, 'class="green-gradient select expandable-list"') . '&nbsp;';

    $start_date_input = '<span id="sales_report_start_date" class="input small-margin-right">
                           <span class="icon-calendar"></span>
                           <input type="text" onfocus="this.select();" name="start_date" id="start_date" placeholder="' . $lC_Language->get("text_from_date") . '" value="' . (isset($cInfo) ? $cInfo->get("start_date") : null) . '" class="input-unstyled datepicker" style="max-width:80px;">
                         </span>';             
    $expires_date_input = '<span id="sales_report_expires_date" class="input">
                             <span class="icon-calendar"></span>
                             <input type="text" name="expires_date" id="expires_date" placeholder="' . $lC_Language->get("text_to_date") . '" value="' . (isset($cInfo) ? $cInfo->get("expires_date") : null) . '" class="input-unstyled datepicker" style="max-width:80px;">
                           </span>';

    $go_date = '<span><input type="button" name="go" id="go" class="button"  onclick="updateList();" value="'. $lC_Language->get("text_go").'" ></span>';

    switch($_GET['module']) {
      case 'orders':
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
      
      case 'products_purchased':
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
      
      case 'products_viewed':
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
        
      case 'customer_orders':
        $list_criteria .= $order_status_dropdown;
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
        
      case 'inventory_cost_margin':
        $list_criteria .= $note;
        $list_criteria .= $breakout;
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
        
      case 'low_stock_report':
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
        
      case 'margin_report_sales':
        $list_criteria .= $note;
        $list_criteria .= $manufacturer_dropdown;
        $list_criteria .= $supplier_dropdown;
        $list_criteria .= $order_status_dropdown;
        $list_criteria .= $time_span_dropdown;
        $list_criteria .= $start_date_input;
        $list_criteria .= $expires_date_input;
        $list_criteria .= $go_date;      
        // added for mobile css per report
        $dataTableTopMargin320 = '0px';
        $dataTableTopMargin480 = '0px';
        break;
        
      case 'sales_report':
        $list_criteria .= $order_status_dropdown;
        $list_criteria .= $time_span_dropdown;
        $list_criteria .= $summary_dropdown;
        $list_criteria .= $start_date_input;
        $list_criteria .= $expires_date_input;
        $list_criteria .= $go_date;
        // added for mobile css per report
        $dataTableTopMargin320 = '0px';
        $dataTableTopMargin480 = '0px';
        break;
        
      case 'sales_tax_report':
        $list_criteria .= $order_status_dropdown;
        $list_criteria .= $time_span_dropdown;
        // added for mobile css per report
        $dataTableTopMargin320 = '-30px';
        $dataTableTopMargin480 = '-30px';
        break;
    }


?>
<!-- Main content -->
<link rel="stylesheet" href="templates/default/css/orders.css">
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1>
    <?php
      if ( empty($_GET['module']) ) {
        echo $lC_Template->getPageTitle();      
      } else {
        echo $lC_Statistics->_title;
      }
    ?>
    </h1>
  </hgroup>
  <style scoped="scoped">
    .dataCol1 { text-align: left; } 
    .dataCol2 { text-align: left; } 
    .dataCol3 { text-align: left; }  
    .dataCol4 { text-align: left; }  
    .dataCol5 { text-align: left; }
    .dataCol5 { text-align: left; }
    .dataTables_info { position:absolute; bottom:42px; color:#4c4c4c; }
    .dataTables_length { float: right; }
    .selectContainer { position:absolute; bottom:29px; left:30px }
    .sorting:before { width: 0; margin-left: 0; }
    .breakout-wrapper { line-height: 24px; }
    #statusSelectorContainer { position:relative !important; left:0; top:0; margin-bottom:5px; }
    @media screen and (min-width: 480px) and (max-width: 767px) {
      #statusSelectorContainer {
        margin-bottom: 40px;
      }
      #dataTable_wrapper { margin-top: <?php echo $dataTableTopMargin480; ?>; }
    }
    @media screen and (max-width: 479px) {
      #statusSelectorContainer {
        margin-bottom: 40px;
      }
      #dataTable_wrapper { margin-top: <?php echo $dataTableTopMargin320; ?>; }
    }
  </style>
  <div class="with-padding-no-top">
    <div id="statusSelectorContainer">
      <div id="statusSelector">
        <form name="orders_status_filter" id="orders_status_filter" action="" onchange="updateList();">
          <?php 
            echo $list_criteria;
          ?>
        </form>
      </div>
    </div>
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <?php
            $cols = 0;
            foreach ( $lC_Statistics->getHeader() as $header ) {
              echo '<th scope="col" class="align-left">' . $header . '</th>';
              $cols++;
            }  
          ?>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="11">&nbsp;</th>
        </tr>
      </tfoot>
    </table>
    </form>
    <div class="clear-both"></div>
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon anthracite-gradient">
                  <span class="icon-reply"></span>
                </span><?php echo $lC_Language->get('button_back'); ?>
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