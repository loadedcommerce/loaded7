<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    
  https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: inventory_cost_margin.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if ( !class_exists('lC_Statistics') ) {
  include($lC_Vqmod->modCheck('includes/classes/statistics.php'));
}

class lC_Statistics_Inventory_Cost_Margin extends lC_Statistics {

  // Class constructor
  public function lC_Statistics_Inventory_Cost_Margin() {
    global $lC_Language, $lC_Currencies, $lC_Vqmod;

    $lC_Language->loadIniFile('modules/statistics/inventory_cost_margin.php');

    if ( !isset($lC_Currencies) ) {
      if ( !class_exists('lC_Currencies') ) {
        include($lC_Vqmod->modCheck('../includes/classes/currencies.php'));
      }

      $lC_Currencies = new lC_Currencies();
    }

    $this->_setIcon();
    $this->_setTitle();
  }

  // Private methods
  protected function _setIcon() {
    $this->_icon = lc_icon_admin('reports.png');
  }

  protected function _setTitle() {
    global $lC_Language;

    $this->_title = $lC_Language->get('statistics_inventory_cost_margin_title');
  }

  protected function _setHeader() {
    global $lC_Language;

    $this->_header = array($lC_Language->get('statistics_inventory_table_heading_breakout'),
                           $lC_Language->get('statistics_inventory_table_heading_of_products'),
                           $lC_Language->get('statistics_inventory_table_heading_total_qoh'),
                           $lC_Language->get('statistics_inventory_table_heading_total_cost'),
                           $lC_Language->get('statistics_inventory_table_heading_retail_value'),
                           $lC_Language->get('statistics_inventory_table_heading_retail_margin'),
                           $lC_Language->get('statistics_inventory_table_heading_retail_special')
                           );
  }

  protected function _setData() {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $this->_data = array();

    $breakoutType = "category";
    if (isset($_GET['breakoutType'])) {
      $breakoutType = $_GET['breakoutType'];
    } 

    switch($breakoutType) {
      case 'class':
        return false;
        break;
      case 'supplier':
        return false;
        break;
      case 'product_sku':
        $breakout_qry = $lC_Database->query('select p.products_id as id, p.products_sku as sku, p.products_quantity as qohTotal,p.products_cost as costTotal, p.products_price as valueTotal, (p.products_price-p.products_cost) as marginTotal, s.specials_new_products_price  as special from :table_products p LEFT JOIN :table_specials s on(s.products_id = p.products_id) order by p.products_sku');        
        $breakout_qry->bindTable(':table_products', TABLE_PRODUCTS);
        $breakout_qry->bindTable(':table_specials', TABLE_SPECIALS);
        $breakout_qry->execute();        
        break;
      case 'manufacturers':
        $breakout_qry = $lC_Database->query('select m.manufacturers_id as id, m.manufacturers_name as name from :table_manufacturers m order by m.manufacturers_name');
        $breakout_qry->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);        
        $breakout_qry->execute();        
        break;
      case 'category':
      default:
        $breakout_qry = $lC_Database->query('select cd.categories_id as id, cd.categories_name as name from :table_categories c, :table_categories_description cd where c.categories_id = cd.categories_id and c.categories_mode = :categories_mode and cd.language_id = :language_id order by cd.categories_name');
        $breakout_qry->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
        $breakout_qry->bindTable(':table_categories', TABLE_CATEGORIES);        
        $breakout_qry->bindInt(':language_id', $lC_Language->getID());
        $breakout_qry->bindvalue(':categories_mode', 'category');
        $breakout_qry->execute();
    }       

    
    while($breakout_qry->next()) {
      if($breakoutType == 'product_sku'){ 
        
        $breakout      = $breakout_qry->value('sku');
        $numOfProduct  = $breakout_qry->value('-');
        $qohTotal      = $breakout_qry->value('qohTotal');
        $costTotal     = $breakout_qry->value('costTotal');
        $valueTotal    = $breakout_qry->value('valueTotal');
        $marginTotal   = $breakout_qry->value('marginTotal');
			  $specialTotal	 = $breakout_qry->value('special'); 
      } else if($breakoutType == 'category' || $breakoutType == 'manufacturers') {
        $id            =	$breakout_qry->value('id');      
        $tmp_arry      = $this->_getDetailsForBreakoutType($id, $breakoutType);
        $breakout      =	$breakout_qry->value('name'); 
        $numOfProduct  = $tmp_arry['numOfProduct'];
        $qohTotal      = $tmp_arry['qohTotal'];
        $costTotal     = $tmp_arry['costTotal'];
        $valueTotal    = $tmp_arry['valueTotal'];
        $marginTotal   = $tmp_arry['marginTotal'];
        $specialTotal  = $tmp_arry['specialTotal'];
      }
      
      $this->_data[] = array($breakout,
                             $numOfProduct,
                             $qohTotal,
                             $lC_Currencies->format($costTotal),
                             $lC_Currencies->format($valueTotal),
                             $lC_Currencies->format($marginTotal),
							               $lC_Currencies->format($specialTotal)
                             );          
		 }     
  }
 
  function _getDetailsForBreakoutType($id, $breakoutType) {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $productDetails_qry = $lC_Database->query('select p.products_id, p.products_quantity, p.products_cost, p.products_price, (p.products_price-p.products_cost) as margin from :table_products p');

    if ($breakoutType == 'category') {
      $productDetails_qry->appendQuery(', :table_products_to_categories ptc '); 
      $productDetails_qry->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
    }
    
    if ($breakoutType == 'manufacturers') {
      $productDetails_qry->appendQuery(' where p.manufacturers_id = :manufacturers_id '); 
      $productDetails_qry->bindValue(':manufacturers_id',$id);
    }
    
    if ($breakoutType == 'category') {
      $productDetails_qry->appendQuery(' where p.products_id = ptc.products_id and ptc.categories_id = :categories_id' ); 
      $productDetails_qry->bindValue(':categories_id',$id);
    }

    $productDetails_qry->bindTable(':table_products', TABLE_PRODUCTS); 
    $productDetails_qry->execute();

		$numOfProduct = 0;
    $qoh = 0;
    $cost = 0;
    $value = 0;
    $margin = 0;
    $specials = 0;

    while($productDetails_qry->next()) {
      $numOfProduct++;
			$qoh += $productDetails_qry->value('products_quantity');
			$cost += $productDetails_qry->value('products_cost');
			$value += $productDetails_qry->value('products_price');
			$margin += $productDetails_qry->value('margin');

      $specails_qry = $lC_Database->query('select specials_new_products_price from :table_specials s where s.products_id = :products_id and (s.expires_date > now() OR s.expires_date IS NULL )');
      $specails_qry->bindTable(':table_specials', TABLE_SPECIALS);
      $specails_qry->bindValue(':products_id',$productDetails_qry->value('products_id'));
      $specails_qry->execute();

      while($specails_qry->next()) {
        $specials += $specails_qry->value('specials_new_products_price');
      }
    }
    
    $return_arr = array('numOfProduct'  => $numOfProduct,
                        'qohTotal'      => $qoh,
                        'costTotal'     => $cost,
                        'valueTotal'    => $value,
                        'marginTotal'   => $margin,
                        'specialTotal'  => $specials
                        );
                        
    return $return_arr;
	}
}
?>