<?php
/**
  @package    admin::graphs
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: banner_daily.php v1.0 2013-08-08 datazen $
*/
function lc_banner_daily($_id, $_month, $_year) {
  global $lC_Database, $lC_Language, $lC_Vqmod;

  require($lC_Vqmod->modCheck('external/panachart/panachart.php'));

  $lC_ObjectInfo = new lC_ObjectInfo(lC_Banner_manager_Admin::getData($_id));

  $image_extension = lc_dynamic_image_extension();
 
  $views = array();
  $clicks = array();
  $vLabels = array();

  $days = @date('t', @mktime(0, 0, 0, $_month))+1;
  $stats = array();

  for ( $i = 1; $i < $days; $i++ ) {
    $stats[] = array($i, '0', '0');

    $views[$i-1] = 0;
    $clicks[$i-1] = 0;
    $vLabels[] = $i;
  }

  $Qstats = $lC_Database->query('select dayofmonth(banners_history_date) as banner_day, banners_shown as value, banners_clicked as dvalue from :table_banners_history where banners_id = :banners_id and month(banners_history_date) = :month and year(banners_history_date) = :year');
  $Qstats->bindTable(':table_banners_history', TABLE_BANNERS_HISTORY);
  $Qstats->bindInt(':banners_id', $_id);
  $Qstats->bindInt(':month', $_month);
  $Qstats->bindInt(':year', $_year);
  $Qstats->execute();

  while ( $Qstats->next() ) {
    $stats[($Qstats->valueInt('banner_day')-1)] = array($Qstats->valueInt('banner_day'), (($Qstats->valueInt('value') > 0) ? $Qstats->valueInt('value') : '0'), (($Qstats->valueInt('dvalue') > 0) ? $Qstats->valueInt('dvalue') : '0'));

    $views[($Qstats->valueInt('banner_day')-1)] = $Qstats->valueInt('value');
    $clicks[($Qstats->valueInt('banner_day')-1)] = $Qstats->valueInt('dvalue');
  }

  $ochart = new chart(600,350, 5, '#eeeeee');
  $ochart->setTitle(sprintf($lC_Language->get('subsection_heading_statistics_daily'), $lC_ObjectInfo->get('banners_title'), @strftime('%B', @mktime(0, 0, 0, $_month)), $_year), '#000000', 2);
  $ochart->setPlotArea(SOLID, '#444444', '#dddddd');
  $ochart->setFormat(0, ',', '.');
  $ochart->setXAxis('#000000', SOLID, 1, '');
  $ochart->setYAxis('#000000', SOLID, 2, '');
  $ochart->setLabels($vLabels, '#000000', 1, VERTICAL);
  $ochart->setGrid('#bbbbbb', DASHED, '#bbbbbb', DOTTED);
  $ochart->addSeries($views, 'area', 'Series1', SOLID, '#000000', '#0000ff');
  $ochart->addSeries($clicks, 'area', 'Series1', SOLID, '#000000', '#ff0000');
  $ochart->plot('images/graphs/banner_daily-' . $_id . '_' . $_month . '.' . $image_extension);

  return $stats;
}
?>