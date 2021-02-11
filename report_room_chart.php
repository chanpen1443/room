<?php
require("config/db.php");

require('function.php');

$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-01");

$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-t");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Room Report</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<?php include("script/css_script.php")?>
	
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
</head>
  
<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include("layout/header.php")?>

  <!-- =============================================== -->
  <!-- Left side column. contains the sidebar -->
  <?php echo asideMenu('report_room_chart');?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">รายงานสถิติการใช้ห้อง</h3>
        </div>
        <div class="box-body">
		
			<div class="">

				<div class="card border-primary">
					<div class="card-body">
					
						<form>

							<div class="row">
							
								<div class="col-md-3">
									<div class="form-group">
										<label>Start Date</label>
										<input type="text" class="form-control datepicker" name="start_date" value="<?php echo $start_date?>">
									</div>
								</div>
								<div class="col-md-3">
								
									<div class="form-group">
										<label>End Date</label>
										<input type="text" class="form-control datepicker" name="end_date" value="<?php echo $end_date?>">
										
									</div>								
								</div>

								<div class="col-md-3">
									<div class="form-group" style="padding-top:25px;">
										<input type="submit" value="Search" class="btn btn-primary">
									</div>
								</div>
								
							</div>
						</form>
						
						<div >
							<canvas id="myChart" height="150" width="300"></canvas>
						</div>
						  
						<table class="table">
							<?php
								$labels = array();
								
								$data = array();

								$sql = "SELECT room.id,room_name,( SELECT COUNT(*) FROM events WHERE DATE(events.start_date) BETWEEN '$start_date' AND '$end_date' AND room_id = room.id ) as total FROM room ";

								$result = mysqli_query($conn, $sql);

								if (mysqli_num_rows($result) > 0) {
									
									echo "<tr class='active'>";

										echo "<th width='5%'>ลำดับ</th>";
										echo "<th width='75%'>ห้องประชุม</th>";
										echo "<th width='10%' class='text-center'>จำนวนครั้ง</th>";
										//echo "<th width='10%' class='text-center'>รายละเอียด</th>";

									echo "</tr>";
									
									$i = 1;
									
									while($row = mysqli_fetch_assoc($result)) {
										
										$labels[] = $row['room_name'];
										
										$data[] = $row['total'];
										
										echo "<tr>";
											echo "<td class='text-center'>".$i."</td>";
											echo "<td>".$row['room_name']."</td>";
											echo "<td class='text-center'>".$row['total']."</td>";
											//echo "<td class='text-center'>รายละเอียด</td>";
										echo "</tr>";
										
										$i++;
									}
									
								} else {
									echo "ไม่พบข้อมูล";
								}

								mysqli_close($conn);
							?>
						</table>	
					
					</div>
				</div>
			</div>		
				
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

	<?php include("layout/footer.php")?>

  <div class="control-sidebar-bg"></div>
  
</div>
<!-- ./wrapper -->

<?php include("script/js_script.php")?>

<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<!-- ChartJS -->
<script src="bower_components/chart.js/Chart.js"></script>

<script>

$(function(){
	
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
		labels : <?php echo json_encode($labels)?>,
		datasets : [
			{
				fillColor : "green",
				data : <?php echo json_encode($data)?>,
				borderWidth:0.5,

			},
		],


	}
	
	var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true,
		yAxes: [{
			categoryPercentage: .8,
			barPercentage: 1,
		}]
	

    }	
		var ctx = document.getElementById("myChart").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, barChartOptions);

			//Date picker
			$('.datepicker').datepicker({
			   format: 'yyyy-mm-dd',
			   autoclose: true
			});

	});
</script>

</body>
</html>
