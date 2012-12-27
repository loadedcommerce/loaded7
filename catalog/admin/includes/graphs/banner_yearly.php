<?php
/*
  $Id: banner_yearly.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

function lc_banner_yearly($_id) {
  global $lC_Database, $lC_Language; 

  require('external/panachart/panachart.php');

  $lC_ObjectInfo = new lC_ObjectInfo(lC_Banner_manager_Admin::getData($_id));

  $image_extension = lc_dynamic_image_extension();

  $views = array();
  $clicks = array();
  $vLabels = array();

  $stats = array();

  $Qstats = $lC_Database->query('select year(banners_history_date) as year, sum(banners_shown) as value, sum(banners_clicked) as dvalue from :table_banners_history where banners_id = :banners_id group by year');
  $Qstats->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
  $Qstats->bindInt(':banners_id', $_id);
  $Qstats->execute();

  while ( $Qstats->next() ) {
    $stats[] = array($Qstats->valueInt('year'), (($Qstats->valueInt('value') > 0) ? $Qstats->valueInt('value') : '0'), (($Qstats->valueInt('dvalue') > 0) ? $Qstats->valueInt('dvalue') : '0'));

    $views[] = $Qstats->valueInt('value');
    $clicks[] = $Qstats->valueInt('dvalue');
    $vLabels[] = $Qstats->valueInt('year');
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf($lC_Language->get('subsection_heading_statistics_yearly'), $lC_ObjectInfo->get('banners_title')), '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, '');
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_yearly-' . $_id . '.' . $image_extension);

  return $stats;
}
?>