<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: date_available.php v1.0 2013-08-08 datazen $
*/
class lC_ProductAttributes_date_available extends lC_Product_attributes_Admin {
  public function __construct() {
    $this->_section = 'dataManagementSettings';
  }

  public function setFunction($value) {
    global $lC_Language;

    if (utility::isPro() === false) {
      $string = '<span class="input large-margin-bottom">
                   <span class="icon-calendar mid-margin-right"></span>
                   ' . lc_draw_input_field('attributes[' . self::getID() . ']', $value, 'id="attributes_' . self::getID() . '" class="input-unstyled datepicker"') . '
                 </span>
                 <span class="input relative disabled date-available-ends margin-bottom">
                   <span class="icon-calendar mid-margin-right"></span>
                   <div class="strong upsellwrapper" style="position:absolute;top:-29px;left:0px;">
                     <span class="upsellinfo" upselltitle="' . $lC_Language->get('text_availability_ends') . '" upselldesc="' . $lC_Language->get('text_availability_ends_desc') . '">Ends</span> ' . lc_go_pro() . '
                   </div>
                   ' . lc_draw_input_field('availability_ends_go_pro', null, 'id="availability_ends_go_pro" class="input-unstyled" disabled') . '
                 </span>
                 ';
    } else {
      $parts = explode('|', $value);
      $string = '<div class="no-wrap"><span class="input large-margin-bottom" style="width:46%;">
                   <span class="icon-calendar mid-margin-right"></span>
                   ' . lc_draw_input_field('attributes[' . self::getID() . ']', $parts[0], 'id="attributes_' . self::getID() . '" class="input-unstyled datepicker"') . '
                 </span>
                 <span class="input relative date-available-ends margin-bottom" style="width:46%;">
                   <span class="icon-calendar mid-margin-right"></span>
                   <div class="strong" style="position:absolute;top:-29px;left:0px;">
                     <span>Ends</span>
                   </div>
                   ' . lc_draw_input_field('attributes[' . self::getID() . '_2]', $parts[1], 'id="attributes_' . self::getID() . '_2" class="input-unstyled datepicker"') . '
                 </span></div>
                 ';
    }
                     
    return $string;
  }
}
?>