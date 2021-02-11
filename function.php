<?php
session_start();//คำสั่งสำหรับเริ่มใช้งาน session

//ตรวจสอบว่าผู้ใช้ Login เข้าสู่ระบบแล้วหรือไม่
function auth(){
	if (!isset($_SESSION["u_id"])) {
		//ถ้ายังไม่ได้ Login ให้ redirect ไปที่ login.php
		header("Location: login.php");
	}
}

//ตรวจสอบว่า เป็นผู้ใช้ระบบ Admin หรือไม่
function checkAuthAdmin(){
	
	auth();
	
	if ($_SESSION["u_role"] != 'admin') {
		header("Location: login.php");
	}
}

//ตรวจสอบว่าเป็นผู้ใช้ระบบ User หรือไม่
function checkAuthUser(){
	
	auth();
	
	if ($_SESSION["u_role"] != 'user') {
		header("Location: login.php");
	}
}

//แปลงสถานะการจองเป็นภาษาไทย
function eventStatusName($status = null){
	
	if($status == 'wait'){
		return 'รออนุมัติ';
	}else if($status == 'approve'){
		return 'อนุมัติ';
	}else if($status == 'not_approved'){
		return 'ไม่อนุมัติ';
	}else if($status == 'cancel'){
		return 'ยกเลิก';
	}
}

//กำหนดสีให้กับสถานะการจอง
function eventStatusColor($status = null){
	
	if($status == 'wait'){
		return '#efc443';
	}else if($status == 'approve'){
		return '#7cbc87';
	}else if($status == 'not_approved'){
		return '#728387';
	}else if($status == 'cancel'){
		return '#ef5f43';
	}
}

//แสดงเมนู
function asideMenu($active = null){
	//ตรวจสอบว่าเป็นผู้ใช้ระบบ User หรือ ไม่ ถ้าใช่ ให้เรียกใช้งาน function asideUserMenu
	if ($_SESSION["u_role"] == 'user') {
		return asideUserMenu($active);
	}
	
	//ตรวจสอบว่าเป็นผู้ใช้ระดับ Admin หรือไม่  ถ้าใช่ ให้เรียกใช้งาน function asideAdminMenu
	if ($_SESSION["u_role"] == 'admin') {
		
		return asideAdminMenu($active);
	}
	
}

//แสดงเมนู สำหรับผู้ใช้ระดับ Admin
function asideAdminMenu($active = null){
	
	$event_add = $active == 'event_add' ? 'active' : '';
	
	$calendar = $active == 'calendar' ? 'active' : '';
	
	$user = $active == 'user' ? 'active' : '';
	
	$room = $active == 'room' ? 'active' : '';
	
	$check_meeting_room = $active == 'check_meeting_room' ?  'active' : '';
	
	$report_room_chart = $active == 'report_room_chart' ?  'active' : '';
	
	$report_department = $active == 'report_department' ?  'active' : '';
	
	$profile = $active == 'profile' ? 'active' : '';
	
	$department = $active == 'department' ? 'active' : '';
	
	$aside = '<aside class="main-sidebar">
				<section class="sidebar">

				  <ul class="sidebar-menu" data-widget="tree">
				  
					<li class="header">MAIN NAVIGATION</li>

					<li class="'.$event_add.'">
					  <a href="event_add.php">
						<i class="fa fa-plus"></i> <span>จองห้อง</span>
					  </a>
					</li>
					<li class="'.$calendar.'" >
					  <a href="calendar.php">
						<i class="fa fa-calendar"></i> <span>ปฏิทินการใช้ห้อง</span>
					  </a>
					</li>
					<li class="treeview '.$check_meeting_room.$report_room_chart.$report_department.'">
					  <a href="#">
						<i class="fa fa-pie-chart"></i>
						<span>รายงาน</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
					  </a>
					  <ul class="treeview-menu">
						<li class="'.$check_meeting_room.'"><a href="check_meeting_room.php"><i class="fa fa-circle-o"></i> การจองใช้ห้องประชุม</a></li>
						<li class="'.$report_room_chart.'"><a href="report_room_chart.php"><i class="fa fa-circle-o"></i> สถิติการใช้ห้องประชุม</a></li>
						<li class="'.$report_department.'"><a href="report_department.php"><i class="fa fa-circle-o"></i> สถิติการใช้ห้องตามหน่วยงาน</a></li>
					  </ul>
					</li>
					<li class="treeview '.$user.$room.$department.'">
					  <a href="#">
						<i class="fa fa-cogs"></i>
						<span>ตั้งค่า</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
					  </a>
					  <ul class="treeview-menu">
						<li class="'.$room.'"><a href="room.php"><i class="fa fa-circle-o"></i> ห้องประชุม</a></li>
						<li class="'.$department.'"><a href="department.php"><i class="fa fa-circle-o"></i> หน่วยงาน/แผนก</a></li>
						<li class="'.$user.'"><a href="user.php"><i class="fa fa-circle-o"></i> ผู้ใช้งานระบบ</a></li>
					  </ul>
					</li>

					<li class="'.$profile.'">
					  <a href="profile.php">
						<i class="fa fa-user"></i> <span>ข้อมูลส่วนตัว</span>
					  </a>
					</li>
					<li>
					  <a href="logout.php">
						<i class="fa fa-sign-out"></i> <span>ออกจากระบบ</span>
					  </a>
					</li>		
					
				  </ul>
				</section>
			  </aside>';
			  
    return $aside;
}

//แสดงเมนู สำหรับผู้ใช้ระดับ User
function asideUserMenu($active = null){
	
	$event_add = $active == 'event_add' ? 'active' : '';
	
	$calendar = $active == 'calendar' ? 'active' : '';
	
	$profile = $active == 'profile' ? 'active' : '';
	
	$aside = '<aside class="main-sidebar">
				<section class="sidebar">

				  <ul class="sidebar-menu" data-widget="tree">
				  
					<li class="header">MAIN NAVIGATION</li>

					<li class="'.$event_add.'">
					  <a href="event_add.php">
						<i class="fa fa-plus"></i> <span>จองห้อง</span>
					  </a>
					</li>
					<li class="'.$calendar.'" >
					  <a href="calendar.php">
						<i class="fa fa-calendar"></i> <span>ปฏิทินการใช้ห้อง</span>
					  </a>
					</li>
					<li class="'.$profile.'">
					  <a href="profile.php">
						<i class="fa fa-user"></i> <span>ข้อมูลส่วนตัว</span>
					  </a>
					</li>
					<li>
					  <a href="logout.php">
						<i class="fa fa-sign-out"></i> <span>ออกจากระบบ</span>
					  </a>
					</li>		
					
				  </ul>
				</section>
			  </aside>';
			  
    return $aside;
}

//แปลง วันที่ และ เวลา เป็น ภาษาไทย
function dateTimeThai($datetime = null){
	
	$date = explode(" ",$datetime);
	
	//ใช้ Function explode ในการแยกไฟล์ ออกเป็น  Array
	$get_date = explode("-",$date[0]);
	//กำหนดชื่อเดือนใส่ตัวแปร $month
	$month = array("01"=>"ม.ค.","02"=>"ก.พ","03"=>"มี.ค.","04"=>"เม.ย.","05"=>"พ.ค.","06"=>"มิ.ย.","07"=>"ก.ค.","08"=>"ส.ค.","09"=>"ก.ย.","10"=>"ต.ค.","11"=>"พ.ย.","12"=>"ธ.ค.");
	//month
	$get_month = $get_date["1"];

	//year	
	$year = $get_date["0"]+543;
	
	$time = substr($date["1"],0,5);
	
	return $get_date["2"]." ".$month[$get_month]." ".$year."  เวลา  ".$time."  น.";
}

//แสดงวันที่เป็นภาษาไทย
function dateThai($date = null){
	
	//ใช้ Function explode ในการแยกไฟล์ ออกเป็น  Array
	$get_date = explode("-",$date);
	//กำหนดชื่อเดือนใส่ตัวแปร $month
	$month = array("01"=>"ม.ค.","02"=>"ก.พ","03"=>"มี.ค.","04"=>"เม.ย.","05"=>"พ.ค.","06"=>"มิ.ย.","07"=>"ก.ค.","08"=>"ส.ค.","09"=>"ก.ย.","10"=>"ต.ค.","11"=>"พ.ย.","12"=>"ธ.ค.");
	//month
	$get_month = $get_date["1"];

	//year	
	$year = $get_date["0"]+543;
	
	return $get_date["2"]." ".$month[$get_month]." ".$year;
}

//ตรวจสอบการจองห้องประชุม ซ้ำ หรือ  กำหนดวันไม่ถูกต้อง
function checkDupBooking($conn,$start_date = null , $end_date = null , $room_id = null ,$update_event_id = null){
	
		$bookNow = true;
		
		$message = "";

		if(!empty($update_event_id)){
					
			$checkDupsql = "SELECT id,room_id,start_date,end_date FROM events WHERE room_id = $room_id AND id NOT IN ($update_event_id) ORDER BY `start_date` DESC";
			
		}else{
			
			$checkDupsql = "SELECT id,room_id,start_date,end_date FROM events WHERE room_id = $room_id ORDER BY `start_date` DESC ";
		}
		
		$resultDup = mysqli_query($conn, $checkDupsql);

		if (mysqli_num_rows($resultDup) > 0) {

			while($row = mysqli_fetch_assoc($resultDup)) {
				
				if($start_date < $end_date && $start_date != $end_date){

					if($row['start_date'] <= $start_date && $row['end_date'] > $start_date){
						
						$message =  "<p class='alert alert-danger'>ขออภัย ห้องที่ท่านเลือกไม่ว่าง   <br>มีการจองใช้ห้อง   วันที่   ".dateTimeThai($row['start_date'])."  ถึง&nbsp; วันที่ ".dateTimeThai($row['end_date'])."<br>กรุณาเลือกช่วงเวลาอื่น</p>";
						
						$bookNow = false;
						break;

					}else{
						
						if($row['start_date'] < $end_date && $row['end_date'] >= $end_date){
								
							$message =  "<p class='alert alert-danger'>ขออภัย ห้องที่ท่านเลือกไม่ว่าง   <br>มีการจองใช้ห้อง   วันที่   ".dateTimeThai($row['start_date'])."  ถึง&nbsp; วันที่ ".dateTimeThai($row['end_date'])."<br>กรุณาเลือกช่วงเวลาอื่น</p>";
							$bookNow = false;
							break;
							
						}else{
							
							if($start_date < $row['start_date'] && $row['end_date'] < $end_date){
								
								$message =  "<p class='alert alert-danger'>ขออภัย ห้องที่ท่านเลือกไม่ว่าง   <br>มีการจองใช้ห้อง   วันที่   ".dateTimeThai($row['start_date'])."  ถึง&nbsp; วันที่ ".dateTimeThai($row['end_date'])."<br>กรุณาเลือกช่วงเวลาอื่น</p>";
								$bookNow = false;
								break;
							}
						}
					}
					
				}else{
					
					$message =  "<p class='alert alert-danger'>ขออภัยคุณเลือกช่วงเวลาจองไม่ถูกต้อง<br>กรุณาเลือกช่วงเวลาอื่น</p>";
					$bookNow = false;
					break;
				}
			}
		}
		
	return array('status' => $bookNow,'message' => $message );
}

//แสดงสถานะการใช้งานของผู้ใช้งานระบบ
function userStatus($status = null){
	
	if($status == 'w'){
		return 'รออนุมัติ';
	}else if($status == 'y'){
		return 'เปิดใช้งาน';
	}else if($status == 'n'){
		return 'ระงับใช้งาน';
	}
}
?>

