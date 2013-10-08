<?php
/*
  $Id: index.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Application_Index class manages the index view
*/  
?>
<script>

// Favicon count
//Tinycon.setBubble(2);
</script>
<?php
require_once($lC_Vqmod->modCheck('includes/applications/index/classes/index.php')); 
?>
<!-- Charts library -->
<!-- Load the AJAX API -->
<script src="https://www.google.com/jsapi"></script>
<script>
/*
* This script is dedicated to building and refreshing the demo chart
*/
// Demo chart
var chartInit = false,
drawVisitorsChart = function()
{
  // Create our data table.
  var data = new google.visualization.DataTable();
  var raw_data = [['Sessions', <?php echo lC_Administrators_Index::get_live_data('Sessions'); ?>],
    ['Customers', <?php echo lC_Administrators_Index::get_live_data('Customers'); ?>],
    ['Carts', <?php echo lC_Administrators_Index::get_live_data('Carts'); ?>],
    ['Orders', <?php echo lC_Administrators_Index::get_live_data('Orders'); ?>]];

  var column_data = [<?php echo lC_Administrators_Index::get_column_data('month'); ?>];

  data.addColumn('string', 'Month');
  for (var i = 0; i < raw_data.length; ++i)
  {
    data.addColumn('number', raw_data[i][0]);
  }

  data.addRows(column_data.length);

  for (var j = 0; j < column_data.length; ++j)
  {
    data.setValue(j, 0, column_data[j]);
  }
  for (var i = 0; i < raw_data.length; ++i)
  {
    for (var j = 1; j < raw_data[i].length; ++j)
    {
      data.setValue(j-1, i+1, raw_data[i][j]);
    }
  }

  // Create and draw the visualization.
  // Learn more on configuration for the LineChart: http://code.google.com/apis/chart/interactive/docs/gallery/linechart.html
  var div = $('#demo-chart'),
  divWidth = div.width();
  new google.visualization.LineChart(div.get(0)).draw(data, {
      title: 'Store Performance :: Current Month',
      width: divWidth,
      height: $.template.mediaQuery.is('mobile') ? 180 : 265,
      legend: 'right',
      yAxis: {title: '(thousands)'},
      backgroundColor: ($.template.ie7 || $.template.ie8) ? '#494C50' : 'transparent',  // IE8 and lower do not support transparency
      legendTextStyle: { color: 'white' },
      titleTextStyle: { color: 'white' },
      hAxis: {
        textStyle: { color: 'white' }
      },
      vAxis: {
        textStyle: { color: 'white' },
        baselineColor: '#666666'
      },
      chartArea: {
        top: 35,
        left: 30,
        width: divWidth-40
      },
      legend: 'bottom'
  });

  // Ready
  chartInit = true;
};

// Load the Visualization API and the piechart package.
google.load('visualization', '1', {
    'packages': ['corechart']
});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawVisitorsChart);

// Watch for block resizing
$('#demo-chart').widthchange(drawVisitorsChart);

// Respond.js hook (media query polyfill)
$(document).on('respond-ready', drawVisitorsChart);

</script>