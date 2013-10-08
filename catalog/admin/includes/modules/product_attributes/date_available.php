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
    global $lC_Language;
    $string = '<div class="columns margin-top">
                 <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                   <span class="input full-width margin-top">
                     <span class="icon-calendar mid-margin-right"></span>
                     ' . lc_draw_input_field('attributes[' . self::getID() . ']', $value, 'id="attributes_' . self::getID() . '" class="input-unstyled datepicker"') . '
                   </span>
                 </div>
                 <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                   <span class="input full-width relative disabled">
                     <span class="icon-calendar mid-margin-right"></span>
                     <div class="strong upsellwrapper" style="position:absolute;top:-30px;left:0px;">
                       <span class="upsellinfo" upselltitle="' . $lC_Language->get('text_availability_ends') . '" upselldesc="' . $lC_Language->get('text_availability_ends_desc') . '">Ends</span> ' . lc_go_pro() . '
                     </div>
                     ' . lc_draw_input_field('availability_ends_go_pro', null, 'id="availability_ends_go_pro" class="input-unstyled" disabled') . '
                   </span>
                 </div>  
               </div>';
    return $string;
  }
}
?>