<?php
require("config/db.php");

require('function.php');

$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d");

$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d");

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
  
<body>
	<h2>รายงานสถิติการใช้ห้องตามหน่วยงาน</h2>
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

					echo "<th width='15%'>วันเริ่ม</th>";
					echo "<th width='15%'>วันสิ้นสุด</th>";
					echo "<th width='25%'>เรื่อง</th>";
					echo "<th width='15%'>ห้องประชุม</th>";
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
</body>
</html>
