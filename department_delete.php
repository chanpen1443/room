
<?php
//เรียกไฟล์ db.php สำหรับเชื่อมต่อฐานข้อมูล
require('config/db.php');

require("function.php");

checkAuthAdmin();

if($_GET['id']){
	
	$id = $_GET['id'];
	
	$sql = "UPDATE department SET status = 0 WHERE id=$id";

	if (mysqli_query($conn, $sql)) {
		
		header("location:department.php?msg=danger");

	} else {
		
		echo "Error deleting record: " . mysqli_error($conn);
	}
	
}
?>
