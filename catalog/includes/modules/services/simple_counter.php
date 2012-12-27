<?php
/*
  $Id$

  LoadedCommerce, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2007 LoadedCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
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
