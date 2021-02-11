<?php
/*
Author: 
Website:
Contact:
*/
session_start();
if(session_destroy()){ // ลบตัวแปร Session ทั้งหมดออกจากระบบ

	header("Location: login.php"); // Redirect ไปยังหน้า login.php
}
?>