<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: simple_counter.php v1.0 2013-08-08 datazen $
*/
class lC_Services_simple_counter {
  function start() {
    global $lC_Database, $lC_MessageStack;

    $Qcounter = $lC_Database->query('select startdate, counter from :table_counter');
    $Qcounter->bindTable(':table_counter', TABLE_COUNTER);
    $Qcounter->execute();

    if ($Qcounter->numberOfRows()) {
      $counter_startdate = $Qcounter->value('startdate');
      $counter_now = $Qcounter->valueInt('counter') + 1;

      $Qcounterupdate = $lC_Database->query('update :table_counter set counter = counter+1');
      $Qcounterupdate->bindTable(':table_counter', TABLE_COUNTER);
      $Qcounterupdate->execute();

      $Qcounterupdate->freeResult();
    } else {
      $counter_startdate = lC_DateTime::getNow();
      $counter_now = 1;

      $Qcounterupdate = $lC_Database->query('insert into :table_counter (startdate, counter) values (:start_date, 1)');
      $Qcounterupdate->bindTable(':table_counter', TABLE_COUNTER);
      $Qcounterupdate->bindValue(':start_date', $counter_startdate);
      $Qcounterupdate->execute();

      $Qcounterupdate->freeResult();
    }

    $Qcounter->freeResult();

    return true;
  }

  function stop() {
    return true;
  }
}
?>