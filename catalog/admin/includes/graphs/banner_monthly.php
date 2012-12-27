<?php
/*
  $Id: banner_monthly.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

function lc_banner_monthly($_id, $_year = '') {
  global $lC_Database, $lC_Language; 

  require('external/panachart/panachart.php');

  $lC_ObjectInfo = new lC_ObjectInfo(lC_Banner_manager_Admin::getData($_id));

  $image_extension = lc_dynamic_image_extension();

  $year = (isset($_year) && $_year != null) ? $_year : @date('Y');

  $stats = array();
  for ( $i = 1; $i < 13; $i++ ) {
    $stats[] = array(strftime('%b', @mktime(0, 0, 0, $i, 1, $year)), '0', '0');
  }

  $views = array();
  $clicks = array();

  $Qstats = $lC_Database->query('select month(banners_history_date) as banner_month, sum(banners_shown) as value, sum(banners_clicked) as dvalue from :table_banners_history where banners_id = :banners_id and year(banners_history_date) = :year group by banner_month');
  $Qstats->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
  $Qstats->bindInt(':banners_id', $_id);
  $Qstats->bindInt(':year', $year);
  $Qstats->execute();

  while ( $Qstats->next() ) {
    $stats[($Qstats->valueInt('banner_month')-1)] = array(strftime('%b', @mktime(0, 0, 0, $Qstats->valueInt('banner_month'), 1, $year)), (($Qstats->valueInt('value') > 0) ? $Qstats->valueInt('value') : '0'), (($Qstats->valueInt('dvalue') > 0) ? $Qstats->valueInt('dvalue') : '0'));

    $views[($Qstats->valueInt('banner_month')-1)] = $Qstats->valueInt('value');
    $clicks[($Qstats->valueInt('banner_month')-1)] = $Qstats->valueInt('dvalue');
  }

  $vLabels = array();

  for ( $i = 1; $i < 13; $i++ ) {
    $vLabels[] = @strftime('%b', @mktime(0, 0, 0, $i, 1, $year));

    if ( !isset($views[$i-1]) ) {
      $views[$i-1] = 0;
    }

    if ( !isset($clicks[$i-1]) ) {
      $clicks[$i-1] = 0;
    }
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf($lC_Language->get('subsection_heading_statistics_monthly'), $lC_ObjectInfo->get('banners_title'), $year), '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, $year);
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_monthly-' . $_id . '.' . $image_extension);

  return $stats;
}
?>