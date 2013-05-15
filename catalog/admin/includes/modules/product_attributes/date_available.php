<?php
/*
  $Id: date_available.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_ProductAttributes_date_available extends lC_Product_attributes_Admin {
  public function __construct() {
    $this->_section = 'dataManagementSettings';
  }

  public function setFunction($value) {
    $string = '<span class="input full-width"><span class="icon-calendar"></span>' . lc_draw_input_field('attributes[' . self::getID() . ']', $value, 'id="attributes_' . self::getID() . '" class="input-unstyled datepicker"') . '</span>';
    return $string;
  }
}
?>