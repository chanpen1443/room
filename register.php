<?php
//นำเข้าไฟล์ db.php สำหรับเชื่อมต่อฐานข้อมูล
require('config/db.php');

//สร้างตัวแปร message สำหรับเก็บข้อความแจ้งเตือน
$message = "";

	if(isset($_POST['username']) && isset($_POST['password'])){

		$fullname = $_POST['fullname'];
		$position = $_POST['position'];
		$department = $_POST['department'];
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$role = 'user';
		$status = 'y';
		$now = date('Y-m-d H:i:s');
		
		//ค้นหาว่า username ที่ผู้ใช้กรอกมานั้นมีอยู่ในระบบแล้วหรือไม่
		$sqlCheckUsername = "SELECT username FROM user WHERE username = '$username'";

		$result = mysqli_query($conn, $sqlCheckUsername);

		if (mysqli_num_rows($result) == 0) {
			
			$sql = "INSERT INTO user (id,fullname,position,department,username,password,role,status,created_at,updated_at)
			VALUES (NULL,'$fullname', '$position', '$department', '$username','$password','$role','$status','$now','$now')";

			if (mysqli_query($conn, $sql)) {
				//กำหนดให้ตัวแปร message เก็บข้อความแจ้งเตือน
				$message = "<p class='alert alert-success text-center'>ลงทะเบียนเรียบร้อยแล้ว <br> คุณสามารถ Login เข้าใช้งานระบบได้ทันที</p>";
	
				
			} else {
				//กรณีเกิดข้อผิดพลาด
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
			
		}else{
			//กำหนดให้ตัวแปร message เก็บข้อความแจ้งเตือน
			$message = "<p class='alert alert-warning'>ขออภัยมีผู้อื่นใช้งาน username นี้แล้ว โปรดใช้ Username อื่น</p>";
		}
		
	}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php
		//เรียกใช้งานไฟล์ css_script.php
		include("script/css_script.php")
	?>
</head>
<body class="hold-transition login-page">
	<div class="login-box">
	  <div class="login-logo">
	   <b>Meeting</b>&nbsp;Calendar
	  </div>
	
	  <div class="login-box-body">
		
		<p class="login-box-msg">ลงทะเบียนใช้งานระบบ</p>
		
		  <?php
			//แสดงข้อความแจ้งเตือนจากตัวแปร message
			echo $message;
		  ?>
		  
		<form method="post">

			<div class="form-group">
				<label>ชื่อ - นามสกุล</label>
				<input type="text" class="form-control" name="fullname" required>
			</div>

			<div class="form-group">
				<label>ตำแหน่ง</label>
				<input type="text" class="form-control" name="position">
			</div>

			<div class="form-group">	
				<label>หน่วยงาน /แผนก</label>
				<select name="department" class="form-control" required>
					 <option value="">-- หน่วยงาน /แผนก --</option>
					<?php
						//ค้นหารายชื่อ
						$sql = "SELECT * FROM department WHERE status = 1";

							$result = mysqli_query($conn, $sql);

							if (mysqli_num_rows($result) > 0) {
			   
								while ($row = mysqli_fetch_assoc($result)) {
									
									$selected = $department == $row['id'] ? 'selected' : '';

									echo "<option value='".$row['id']."' $selected>".$row['department_name']."</option>";
								}
							} else {
								echo "0 results";
							}
					?>
				</select>
			</div>	

			<div class="form-group">
				<label>Username</label>
				<input type="text" class="form-control" name="username" required>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="password" class="form-control" name="password" required>
			</div>

		
			<div class="form-group ">
				<button type="submit" class="btn btn-success btn-block btn-flat">ลงทะเบียน</button>
			</div>
		</form>
			<hr>
		<a href="login.php" class="btn btn-default btn-block btn-flat">เข้าสู่ระบบ กรณีมีบัญชีผู้ใช้อยู่แล้ว</a>

	  </div>
	  <!-- /.login-box-body -->
	</div>

</body>
</html>
