<?php
require('security/session.php');
require('security/user.php');
$user = new User();
if (!$user->isLoggedIn()){
  header('location: index.php');
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/main.css" />
<title>ESF Dream Camps Evaluation Reporting Dashboard</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
  <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
// Load the Visualization API and the controls package.
google.load('visualization', '1.0', {'packages':['controls']});
function drawVisualization() {
  control1 = createDashboard1();
  control2 = createDashboard2();

  google.visualization.events.addListener(control1, 'statechange', function() {
    control2.setState(control1.getState());
    control2.draw();
  });
}

function createDashboard1()
{
  $.post("fetchdata/slide27.php",
{
  type: "line"
},
function (result)
{
  var data = new google.visualization.DataTable(result);

  var formatter = new google.visualization.NumberFormat({pattern: '0%'});

  formatter.format(data, 1);

  var options = {
    title: 'Years attending ESF Dream Camp',
      vAxis: {title: 'Percentage', format:'#%'},
      legend: {position: 'none'},
      colors:['#3366CC']
  };

  var stringFilter = new google.visualization.ControlWrapper({
    'controlType': 'StringFilter',
      'containerId': 'control1',
      'options': {
        'filterColumnLabel': 'Years attending ESF Dream Camp'
      }
  });

  var chart = new google.visualization.ChartWrapper();
  chart.setChartType('ColumnChart');
  chart.setDataTable(data);
  chart.setContainerId('chart1');
  chart.setOptions(options);

  // Create the dashboard.
  var dashboard = new
    google.visualization.Dashboard(document.getElementById('dashboard'));
  // Configure the string filter to affect the table contents
  dashboard.bind(stringFilter, chart);
  // Draw the dashboard
  dashboard.draw(data);
  return stringFilter;
}, "json"
);
}
function createDashboard2()
{
  $.post("fetchdata/slide26.php",
{
  type: "line"
},
function (result)
{
  var data = new google.visualization.DataTable(result);

  var formatter = new google.visualization.NumberFormat({pattern: '0%'});

  formatter.format(data, 1);


  var options = {
    legend: {position: 'top', alignment: 'center'},
      colors:['#3366CC', '#FF9900', '#31a354']
  };

  var stringFilter = new google.visualization.ControlWrapper({
    'controlType': 'StringFilter',
      'containerId': 'control2',
      'options': {
        'filterColumnLabel': 'Frequence'
      }
  });

  var chart = new google.visualization.ChartWrapper();
  chart.setChartType('PieChart');
  chart.setDataTable(data);
  chart.setContainerId('chart2');
  chart.setOptions(options);

  // Create the dashboard.
  var dashboard = new
    google.visualization.Dashboard(document.getElementById('dashboard'));
  // Configure the string filter to affect the table contents
  dashboard.bind(stringFilter, chart);
  // Draw the dashboard
  dashboard.draw(data);
  return stringFilter;
}, "json"
);
}
google.setOnLoadCallback(drawVisualization);
</script>
<style>
  #Loading {
    background:url(http://loadinggif.com/images/image-selection/36.gif) no-repeat center center;
    height: 100px;
    width: 100px;
    position: fixed;
    left: 50%;
    top: 50%;
    margin: -25px 0 0 -25px;
    z-index: 1000;
  }
  </style>
</head>
<body>
<div id="Loading" style="display:none"></div>
<div class="overflow">
      <div id="banner">
        <div id="banner-head"></div>
        <h1>
          <span id="pottstown">ESF Dream</span> <span id="health">Evaluation Reporting System</span>
        </h1>
        <nav>
          <div id="nav-left">
            <a href="background.php">Background Data</a>
            <a href="outcome.php">Outcome Data</a>
          </div>
          <div id="nav-right">
            <a href="#support">Support</a>
            <a href="logout.php">Log out</a>
          </div>
        </nav>
        <div id="background"></div>
        <div id="outcome"></div>
        <div id="support"></div>
      </div>
      <div id="dashboard">
        <table>
          <tr>
          <td>
            <div id="control1" style="display:none"></div>
            <div id="control2" style="display:none"></div>
          </td>
          <td>
            <div style="width: 500px; height: 300px;" id="chart1"></div>
          </td>
          <td>
            <div style="width: 600px; height: 300px;" id="chart2"></div>
          </td>
          <td>
          </td>
        </tr>
      </table>
    </div>
</body>
</html>
