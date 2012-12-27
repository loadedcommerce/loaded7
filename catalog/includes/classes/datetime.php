<?php
/*
  $Id: datetime.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_DateTime {
    function getNow() {
      return @date('Y-m-d H:i:s');
    }

    function getShort($date = null, $with_time = false) {
      global $lC_Language;

      if (empty($date)) {
        $date = lC_DateTime::getNow();
      }

      $year = substr($date, 0, 4);
      $month = (int)substr($date, 5, 2);
      $day = (int)substr($date, 8, 2);
      $hour = (int)substr($date, 11, 2);
      $minute = (int)substr($date, 14, 2);
      $second = (int)substr($date, 17, 2);

      if (@date('Y', @mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
        return @strftime($lC_Language->getDateFormatShort($with_time), @mktime($hour, $minute, $second, $month, $day, $year));
      } else {
        return preg_replace('/2037/', $year, @strftime($lC_Language->getDateFormatShort($with_time), @mktime($hour, $minute, $second, $month, $day, 2037)));
      }
    }

    function getLong($date = '', $with_time = false) {
      global $lC_Language;

      if (empty($date)) {
        $date = lC_DateTime::getNow();
      }

      $year = substr($date, 0, 4);
      $month = (int)substr($date, 5, 2);
      $day = (int)substr($date, 8, 2);
      $hour = (int)substr($date, 11, 2);
      $minute = (int)substr($date, 14, 2);
      $second = (int)substr($date, 17, 2);

      if (@date('Y', @mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
        return @strftime((($with_time) ? $lC_Language->getDateFormatLong() : substr($lC_Language->getDateFormatLong(), 0, strpos($lC_Language->getDateFormatLong(), " at"))), @mktime($hour, $minute, $second, $month, $day, $year));
      } else {
        return preg_replace('/2037/', $year, @strftime((($with_time) ? $lC_Language->getDateFormatLong() : substr($lC_Language->getDateFormatLong(), 0, strpos($lC_Language->getDateFormatLong(), " at"))), @mktime($hour, $minute, $second, $month, $day, 2037)));
      }
    }

    function getTimestamp($date = '') {
      global $lC_Language;

      if (empty($date)) {
        $date = lC_DateTime::getNow();
      }

      $year = substr($date, 0, 4);
      $month = (int)substr($date, 5, 2);
      $day = (int)substr($date, 8, 2);
      $hour = (int)substr($date, 11, 2);
      $minute = (int)substr($date, 14, 2);
      $second = (int)substr($date, 17, 2);

      return @mktime($hour, $minute, $second, $month, $day, $year);
    }

    function fromUnixTimestamp($timestamp) {
      return @date('Y-m-d H:i:s', $timestamp);
    }

    function toDatetime($date = '', $delimiter = '/') {
      if (empty($date)) {
        $date = lC_DateTime::getNow();
        $dateArr = explode('-', $date);
        $dateString = $dateArr[0] . '-' . $dateArr[1] . '-' . $dateArr[2];
      } else {
        $dateArr = explode($delimiter, $date);
        $dateString = $dateArr[2] . '-' . $dateArr[0] . '-' . $dateArr[1];
      }

      return $dateString;
    }

    function isLeapYear($year = '') {
      if (empty($year)) {
        $year = $this->year;
      }

      if ($year % 100 == 0) {
        if ($year % 400 == 0) {
          return true;
        }
      } else {
        if (($year % 4) == 0) {
          return true;
        }
      }

      return false;
    }

    function validate($date_to_check, $format_string, &$date_array) {
      $separator_idx = -1;

      $separators = array('-', ' ', '/', '.');
      $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
      $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

      $format_string = strtolower($format_string);

      if (strlen($date_to_check) != strlen($format_string)) {
        return false;
      }

      $size = sizeof($separators);
      for ($i=0; $i<$size; $i++) {
        $pos_separator = strpos($date_to_check, $separators[$i]);
        if ($pos_separator != false) {
          $date_separator_idx = $i;
          break;
        }
      }

      for ($i=0; $i<$size; $i++) {
        $pos_separator = strpos($format_string, $separators[$i]);
        if ($pos_separator != false) {
          $format_separator_idx = $i;
          break;
        }
      }

      if ($date_separator_idx != $format_separator_idx) {
        return false;
      }

      if ($date_separator_idx != -1) {
        $format_string_array = explode( $separators[$date_separator_idx], $format_string );
        if (sizeof($format_string_array) != 3) {
          return false;
        }

        $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
        if (sizeof($date_to_check_array) != 3) {
          return false;
        }

        $size = sizeof($format_string_array);
        for ($i=0; $i<$size; $i++) {
          if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
          if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
          if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
        }
      } else {
        if (strlen($format_string) == 8 || strlen($format_string) == 9) {
          $pos_month = strpos($format_string, 'mmm');
          if ($pos_month != false) {
            $month = substr( $date_to_check, $pos_month, 3 );
            $size = sizeof($month_abbr);
            for ($i=0; $i<$size; $i++) {
              if ($month == $month_abbr[$i]) {
                $month = $i;
                break;
              }
            }
          } else {
            $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
          }
        } else {
          return false;
        }

        $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
        $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
      }

      if (strlen($year) != 4) {
        return false;
      }

      if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
        return false;
      }

      if ($month > 12 || $month < 1) {
        return false;
      }

      if ($day < 1) {
        return false;
      }

      if ($this->isLeapYear($year)) {
        $no_of_days[1] = 29;
      }

      if ($day > $no_of_days[$month - 1]) {
        return false;
      }

      $date_array = array($year, $month, $day);

      return true;
    }
  }
?>