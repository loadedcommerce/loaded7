<?php
/**
  @package    catalog::modules::variants
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: multiple_file_upload.php v1.0 2013-08-08 datazen $
*/
class lC_Variants_multiple_file_upload extends lC_Variants_Abstract {
  const ALLOW_MULTIPLE_VALUES = false;
  const HAS_CUSTOM_VALUE = true;

  static public function parse($data) {
    
    $default_value = null;
      
    $string = '';

    return $string;
  }

  static public function allowsMultipleValues() {
    return self::ALLOW_MULTIPLE_VALUES;
  }

  static public function hasCustomValue() {
    return self::HAS_CUSTOM_VALUE;
  }
}
?>