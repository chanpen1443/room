<?php
require("config/db.php");

require('function.php');

$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-01");

$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-t");

$department_id = !empty($_GET['department_id']) ? $_GET['department_id'] : 0;

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
  <?php echo asideMenu('report_department');?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">รายงานสถิติการใช้ห้องตามหน่วยงาน</h3>
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
								
									<div class="form-group">
										<label>Select Department</label>
											<select name="department_id" class="form-control" required>
												 <option value="">-- Select Department --</option>
												<?php
														$sql = "SELECT * FROM department WHERE status = 1";

															$result = mysqli_query($conn, $sql);

															if (mysqli_num_rows($result) > 0) {
											   
																while ($row = mysqli_fetch_assoc($result)) {
																	
																	if($row['id'] == $department_id){
																		
																		echo "<option value='".$row['id']."' selected>".$row['department_name']."</option>";
																		
																	}else{
																		
																		echo "<option value='".$row['id']."'>".$row['department_name']."</option>";
																	}

																}
															}
												?>
											</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group" style="padding-top:25px;">
									
										<input type="submit" value="Search" class="btn btn-primary">
									
										<div class="btn-group">
										  <button class="btn btn-success btn-flat" type="button">Export</button>
										  <button class="btn btn-success btn-flat dropdown-toggle" aria-expanded="false" type="button" data-toggle="dropdown">
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										  </button>
										  <ul class="dropdown-menu" role="menu">
											<li><a href="report_department_print.php?start_date=<?php echo $start_date?>&end_date=<?php echo $end_date?>&department_id=<?php echo $department_id?>" target="_blank">Print</a></li>
											<li><a href="report_department_export.php?start_date=<?php echo $start_date?>&end_date=<?php echo $end_date?>&department_id=<?php echo $department_id?>">Export Excel</a></li>
									
										  </ul>
										</div>
									</div>
								</div>
								
							</div>
						</form>
						
						<table class="table">
							<?php

								$sql = "SELECT events.id,DATE(start_date) as s_date,DATE(end_date) as e_date,TIME_FORMAT(start_date, '%h:%i') as start_time,
										TIME_FORMAT(end_date, '%h:%i')  as end_time,title,room_name,department_name,events.status FROM `events` 
										INNER JOIN room ON room.id = events.room_id
										INNER JOIN user ON user.id = events.user_id
										INNER JOIN department ON department.id = user.department
										WHERE DATE(start_date) BETWEEN '$start_date' AND '$end_date' AND department.id = $department_id";

								$result = mysqli_query($conn, $sql);

								if (mysqli_num_rows($result) > 0) {
									
									echo "<tr class='active'>";

										echo "<th width='10%'>วันที่เริ่ม</th>";
										echo "<th width='10%'>วันที่สิ้นสุด</th>";
										echo "<th width='30%'>เรื่อง</th>";
										echo "<th width='20%'>ห้องประชุม</th>";
										echo "<th width='20%'>หน่วยงาน</th>";
										echo "<th width='10%'>สถานะ</th>";
									echo "</tr>";
									
									$i = 1;
									
									while($row = mysqli_fetch_assoc($result)) {
										

										echo "<tr>";
											echo "<td>".dateThai($row['s_date'])."<br>เวลา  ".$row['start_time']." น.</td>";
											echo "<td>".dateThai($row['e_date'])."<br> เวลา  ".$row['end_time']." น.</td>";
											echo "<td>".$row['title']."</td>";
											echo "<td>".$row['room_name']."</td>";
											echo "<td>".$row['department_name']."</td>";
											echo "<td>".eventStatusName($row['status'])."</td>";
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

<script>

$(function(){
	
			//Date picker
			$('.datepicker').datepicker({
			   format: 'yyyy-mm-dd',
			   autoclose: true
			});

});
</script>

</body>
</html>
