<?php
    require_once 'functions.php';
    $con = mysql_connect("localhost","root","");
        if(!$con){
           die("Connection failed! ".mysql_error());
        }
        $select_db = mysql_select_db("stats",$con);
        if(!$select_db){
            die("Data base is not selected! ".mysql_error());
        }
        $stat = getStatData("products");
?>
    <style>
        th{width:5.5%;}
        th.keyword{width:15%;}
        th.chart{width:8%;}
    </style>
    <table border="1px solid navy" width="100%">
        <tr>
                <th class="keyword">KEYWORD</th>
                <th>14</th>
                <th>13</th>
                <th>12</th>
                <th>11</th>
                <th>10</th>
                <th>9</th>
                <th>8</th>
                <th>7</th>
                <th>6</th>
                <th>5</th>
                <th>4</th>
                <th>3</th>
                <th>2</th>
                <th>1</th>
                <th class="chart">CHART</th>

        </tr>

        <?php foreach($stat as $keyword=>$imps){ ?>
            <tr><td><?php echo $keyword; ?></td><?php for($j=0;$j<14;$j++){ ?><td><?php echo $imps[$j]; ?></td><?php }  ?><td><a class="view" href="#">View</a></td></tr>
        <?php }  ?>

    </table>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>stats</title>
        <link rel="stylesheet" href="css/style.css" type="text/css">]
        <link rel="stylesheet" href="css/jqueryui.css" type="text/css">
        <style>
           .ui-widget-overlay
{
  opacity: .50 !important; /* Make sure to change both of these, as IE only sees the second one */
  filter: Alpha(Opacity=50) !important;

  background-color: rgb(20, 20, 20) !important; /* This will make it darker */
}
        </style>
        <script src="js/amcharts.js" type="text/javascript"></script>  
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/jqueryui.js" type="text/javascript"></script>
   
        <script type="text/javascript">
         
        jQuery(function(){
            //$('#chartdiv').hide();
            $('#chartdiv').css('visibility', 'hidden');
            $('.ui-widget-overlay.ui-front').live('click',function(){
                $( "#chartdiv" ).dialog('close');
            });
            $('.view').click(function(e){
                $('#chartdiv').empty();
                //$(this).click(); 
            //$('#chartdiv').html("");
            data = [];
            for(i=0;i<14;i++){
                index = i+2;
                current_td_value = $(this).parent().parent().find('td:nth-child('+index+')').html();
                obj = {};
                obj.date        = new Date(2012,0,i+1);
                obj.impressions = current_td_value;
                
                data.push(obj);

            } // End for
           
            $('#chartdiv').css('visibility', 'visible');
            
            $( "#chartdiv" ).dialog({width: 1200,height: 400,modal:true,close: function() {  $('#chartdiv').css('visibility', 'hidden'); }});
                     // $( "#chartdiv" ).dialog('destroy');
            e.preventDefault();
            drawChart(data);
            //drawChart(data);
            });
        });

 
        function drawChart(chart_data){
            var chartData = chart_data;
            var chart;

        //AmCharts.ready(function () {
            // SERIAL CHART
            chart = new AmCharts.AmSerialChart();
            chart.dataProvider = chartData;
            chart.categoryField = "date";
            chart.marginTop = 0;

            // AXES
            // category axis
             categoryAxis = chart.categoryAxis;
            categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
            categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD                
            categoryAxis.autoGridCount = false;
            categoryAxis.gridCount = 50;
            categoryAxis.gridAlpha = 0;
            categoryAxis.gridColor = "#000000";
            categoryAxis.axisColor = "#555555";
            // we want custom date formatting, so we change it in next line
            categoryAxis.dateFormats = [{
                period: 'DD',
                format: 'DD'
            }, {
                period: 'WW',
                format: 'MMM DD'
            }, {
                period: 'MM',
                format: 'MMM'
            }, {
                period: 'YYYY',
                format: 'YYYY'
            }];

            // as we have data of different units, we create two different value axes
            // Duration value axis            
             impressionsAxis = new AmCharts.ValueAxis();
            impressionsAxis.title = "impressions";
            impressionsAxis.gridAlpha = 0.05;
            impressionsAxis.axisAlpha = 0;
            impressionsAxis.inside = true;
            // the following line makes this value axis to convert values to impressions
            // it tells the axis what impressions unit it should use. mm - minute, hh - hour...                
            impressionsAxis.impressions = "mm";
            impressionsAxis.impressionsUnits = {
                DD: "d. ",
                hh: "h ",
                mm: "min",
                ss: ""
            };
            chart.addValueAxis(impressionsAxis);

            // GRAPHS
            // impressions graph
             impressionsGraph = new AmCharts.AmGraph();
            impressionsGraph.title = "impressions";
            impressionsGraph.valueField = "impressions";
            impressionsGraph.type = "line";
            impressionsGraph.valueAxis = impressionsAxis; // indicate which axis should be used
            impressionsGraph.lineColor = "#CC0000";
            impressionsGraph.balloonText = "[[value]]";
            impressionsGraph.lineThickness = 1;
            impressionsGraph.legendValueText = "[[value]]";
            impressionsGraph.bullet = "square";
            chart.addGraph(impressionsGraph);

            // CURSOR                
             chartCursor = new AmCharts.ChartCursor();
            chartCursor.zoomable = false;
            chartCursor.categoryBalloonDateFormat = "DD";
            chartCursor.cursorAlpha = 0;
            chart.addChartCursor(chartCursor);

            // LEGEND
             legend = new AmCharts.AmLegend();
            legend.bulletType = "round";
            legend.equalWidths = false;
            legend.valueWidth = 120;
            legend.color = "#000000";
            chart.addLegend(legend);

            // WRITE                                
            chart.write("chartdiv");
       // });   
        }
            
           //' drawChart(5345);
        </script>
    </head>
    
    <body>
        <div id="chartdiv" style="width:90%; height:400px;background-color: whitesmoke"></div>
    </body>

</html>