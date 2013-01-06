<?php
/*
  $Id: weight.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Weight {
    var $weight_classes = array(),
        $precision;

    // class constructor
    function lC_Weight($precision = '2') {
      $this->precision = $precision;

      $this->prepareRules();
    }

    function getTitle($id) {
      global $lC_Database, $lC_Language;

      $Qweight = $lC_Database->query('select weight_class_title from :table_weight_class where weight_class_id = :weight_class_id and language_id = :language_id');
      $Qweight->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
      $Qweight->bindInt(':weight_class_id', $id);
      $Qweight->bindInt(':language_id', $lC_Language->getID());
      $Qweight->execute();

      return $Qweight->value('weight_class_title');
    }

    function prepareRules() {
      global $lC_Database, $lC_Language;

      $Qrules = $lC_Database->query('select r.weight_class_from_id, r.weight_class_to_id, r.weight_class_rule from :table_weight_class_rules r, :table_weight_class c where c.weight_class_id = r.weight_class_from_id');
      $Qrules->bindTable(':table_weight_class_rules', TABLE_WEIGHT_CLASS_RULES);
      $Qrules->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
      $Qrules->setCache('weight-rules');
      $Qrules->execute();

      while ($Qrules->next()) {
        $this->weight_classes[$Qrules->valueInt('weight_class_from_id')][$Qrules->valueInt('weight_class_to_id')] = $Qrules->value('weight_class_rule');
      }

      $Qclasses = $lC_Database->query('select weight_class_id, weight_class_key, weight_class_title from :table_weight_class where language_id = :language_id');
      $Qclasses->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
      $Qclasses->bindInt(':language_id', $lC_Language->getID());
      $Qclasses->setCache('weight-classes');
      $Qclasses->execute();

      while ($Qclasses->next()) {
        $this->weight_classes[$Qclasses->valueInt('weight_class_id')]['key'] = $Qclasses->value('weight_class_key');
        $this->weight_classes[$Qclasses->valueInt('weight_class_id')]['title'] = $Qclasses->value('weight_class_title');
      }

      $Qrules->freeResult();
      $Qclasses->freeResult();
    }

    function convert($value, $unit_from, $unit_to) {
      global $lC_Language;

      if ($unit_from == $unit_to) {
        return number_format($value, (int)$this->precision, $lC_Language->getNumericDecimalSeparator(), $lC_Language->getNumericThousandsSeparator());
      } else {
        return number_format($value * $this->weight_classes[(int)$unit_from][(int)$unit_to], (int)$this->precision, $lC_Language->getNumericDecimalSeparator(), $lC_Language->getNumericThousandsSeparator());
      }
    }

    function display($value, $class) {
      global $lC_Language;

      return number_format($value, (int)$this->precision, $lC_Language->getNumericDecimalSeparator(), $lC_Language->getNumericThousandsSeparator()) . $this->weight_classes[$class]['key'];
    }

    function getClasses() {
      global $lC_Database, $lC_Language;

      $weight_class_array = array();

      $Qclasses = $lC_Database->query('select weight_class_id, weight_class_title from :table_weight_class where language_id = :language_id order by weight_class_title');
      $Qclasses->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
      $Qclasses->bindInt(':language_id', $lC_Language->getID());
      $Qclasses->execute();

      while ($Qclasses->next()) {
        $weight_class_array[] = array('id' => $Qclasses->valueInt('weight_class_id'),
                                      'title' => $Qclasses->value('weight_class_title'));
      }

      return $weight_class_array;
    }
  }
?>