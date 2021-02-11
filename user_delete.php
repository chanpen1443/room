
<?php
//เรียกไฟล์ db.php สำหรับเชื่อมต่อฐานข้อมูล
require('config/db.php');

require("function.php");

checkAuthAdmin();

if($_GET['id']){
	
	$id = $_GET['id'];
	
	//คำสั่ง SQL ลบข้อมูลผู้ใช้งานที่มี id เท่ากับ ค่าตัวแปร id ที่ส่งมา
	$sql = "UPDATE user SET status = 'delete' WHERE id=$id";

	if (mysqli_query($conn, $sql)) {
		
		header("location:user.php?msg=danger");

	} else {
		
		echo "Error deleting record: " . mysqli_error($conn);
	}
	
}
?>
