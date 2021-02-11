<?php
require("config/db.php");

require('function.php');

$room_id = !empty($_GET['room_id']) ? $_GET['room_id'] : 'all';

$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d");

$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<?php include("script/css_script.php")?>
	
</head>
  
<body>
	<h2>รายงานการจองใช้ห้องประชุม</h2>
	<table class="table">
		<?php
			
			if($room_id == 'all'){
				
				$sql = "SELECT events.id,fullname,room_name,title,DATE(start_date) as start_date,DATE(end_date) as end_date,TIME_FORMAT(start_date, '%h:%i') as start_time,TIME_FORMAT(end_date, '%h:%i')  as end_time,detail,person_use,events.status FROM events
				INNER JOIN room ON room.id = events.room_id 
				INNER JOIN user ON user.id = events.user_id WHERE DATE(start_date) BETWEEN '$start_date' AND '$end_date' ORDER BY start_date,start_time ASC";

			}else{
				$sql = "SELECT events.id,fullname,room_name,title,DATE(start_date) as start_date,DATE(end_date) as end_date,TIME_FORMAT(start_date, '%h:%i') as start_time,TIME_FORMAT(end_date, '%h:%i')  as end_time,detail,person_use,events.status FROM events
				INNER JOIN room ON room.id = events.room_id 
				INNER JOIN user ON user.id = events.user_id  WHERE DATE(start_date) BETWEEN '$start_date' AND '$end_date' AND events.room_id = $room_id ORDER BY start_date,start_time ASC";
			}
				
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
				echo "<thead>";
					echo "<tr class='active'>";

						echo "<th width='15%'>วันที่เริ่ม</th>";
						echo "<th width='15%'>วันที่สิ้นสุด</th>";
						echo "<th width='20%'>เรื่อง</th>";
						echo "<th width='20%'>ห้องประชุม</th>";
						echo "<th width='20%'>ผู้บันทึกข้อมูล</th>";
						echo "<th width='10%'>สถานะ</th>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
								
				$i = 1;
				while($row = mysqli_fetch_assoc($result)) {
					
					echo "<tr>";
						
						echo "<td>".dateThai($row['start_date'])."<br>เวลา  ".$row['start_time']." น.</td>";
						echo "<td>".dateThai($row['end_date'])."<br> เวลา  ".$row['end_time']." น.</td>";
						echo "<td>".$row['title']."</td>";
						echo "<td>".$row['room_name']."</td>";
						echo "<td>".$row['fullname']."</td>";
						echo "<td>".eventStatusName($row['status'])."</td>";
					echo "</tr>";
					
					$i++;
				}
				
			} else {
				echo "ไม่พบข้อมูล";
			}

			mysqli_close($conn);
		?>
		</tbody>
	</table>	
</body>
</html>
