<?php

require("config/db.php"); //นำเข้าไฟล์ db.php สำหรับเชื่อมต่อฐานข้อมูล

require("function.php"); //เรียกใช้งานไฟล์ function.php

auth(); //ตรวจสอบว่าผู้ใช้งาน Login เข้าสู่ระบบแล้วหรือไม่

if (isset($_GET['event_id'])) {

	$data = [];

    if (!empty($_GET['event_id'])) {

        $event_id = $_GET['event_id'];

        //Find One Event
        $sql = "SELECT events.id,user_id,title,DATE(start_date) as start_date,DATE(end_date) as end_date,
		TIME(start_date) as start_time , TIME(end_date) as end_time,detail,person_use,events.status,
				room.room_name,user.fullname,user.department,equipment,room.room_image,department.department_name
				FROM events 
				INNER JOIN room ON room.id = events.room_id 
				INNER JOIN user ON user.id = events.user_id 
                LEFT JOIN department On user.department = department.id
                WHERE events.id = $event_id";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            $data = [
                'id' => $row['id'],
                'title' => $row['title'],
                'start' => $row['start_date'],
                'end' => date('Y-m-d',strtotime("+1 day",strtotime($row['end_date']))),
                'booking_start' => dateTimeThai($row['start_date'] . " " . $row['start_time']),
                'booking_end' => dateTimeThai($row['end_date'] . " " . $row['end_time']),
                'description' => $row['detail'],
                'room_name' => $row['room_name'],
                'room_image' => $row['room_image'],
                'fullname' => $row['fullname'],
                'department' => $row['department_name'],
                'person_use' => $row['person_use'],
                'status' => eventStatusName($row['status']),
                'textColor' => '#FFF',
                'borderColor' => '#FFF',
                'backgroundColor' => eventStatusColor($row['status']),
                'className' => 'calendarText',

            ];
        }

    }
}else{
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Print</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<style>
	@media print  {  
		#non-printable { 
			display: none; 
		} 
	}
		table tr th{
			padding:15px;
			font-size:16px;
		}
	</style>
</head>
  
<body>


		<h3 class="text-center">ใบขอใช้ห้องประชุม
			<div class="text-right" id="non-printable">
					<button class="btn btn-info btn-sm" onclick="return window.print();"><i class="glyphicon glyphicon-print"></i> พิมพ์</button>
					<button class="btn btn-default btn-sm" onclick="return window.close();"><i class="glyphicon glyphicon-off"></i> ปิด</button>
			</div>
		</h3>
		<?php
			if(!empty($data)){
		?>
		<table>
			<tr>
				<th width="40%">ผู้บันทึกข้อมูล : </th>
				<td id="fullname"><?=$data['fullname']?></td>
			</tr>
			<tr>
				<th>หน่วยงาน</th>
				<td id="department"><?=$data['department']?></td>
			</tr>
			<tr>
				<th >เรื่อง</th>
				<td id="title"><?=$data['title']?></td>
			</tr>
			<tr>
				<th>เริ่มใช้ห้อง</th>
				<td id="booking_start"><?=$data['booking_start']?></td>
			</tr>
			<tr>
				<th>สิ้นสุดการใช้ห้อง</th>
				<td id="booking_end"><?=$data['booking_end']?></td>
			</tr>
			<tr>
				<th>ชื่อห้องประชุม</th>
				<td  id="room_name"><?=$data['room_name']?></td>
			</tr>
			<tr>
				<th>จำนวนผู้ประชุม</th>
				<td id="person_use"><?=$data['person_use']?></td>
			</tr>

			<tr>
				<th>รายละเอียด</th>
				<td id="description"><?=$data['description']?></td>
			</tr>


			<!--<tr>
				<th>อุปกรณ์เพิ่มเติม</th>
				<td id="equipment"></td>
			</tr>-->

		</table>
				<table id="print-table" style="font-size:16px;text-align:center;margin-top:50px;" width="100%">
					<tr>

							<td style="width:40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...............................................</td>

							<td style="width:20%"></td>

							<td style="width:40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...............................................</td>
					</tr>
					<tr>

							<td style="width:40%;padding-top:15px" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...............................................)</td>

							<td style="width:20%;padding-top:15px" ></td>

							<td style="width:40%;padding-top:15px" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...............................................)</td>
					</tr>
					<tr>

							<td style="width:40%">ผู้ขอใช้ห้อง</td>

							<td style="width:20%"></td>

							<td style="width:40%">ผู้อนุมัติ</td>
					</tr>

				</table>
		<?php
			}
		?>

</body>
</html>