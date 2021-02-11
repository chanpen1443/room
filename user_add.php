<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();
 
 $message = "";

 //รับข้อมูลจากฟอร์มด้านล่าง   ตรวจสอบว่ามีการส่งตัวแปร username และ password มาหรือไม่
if(isset($_POST['username']) && isset($_POST['password'])){

	$fullname = $_POST['fullname'];
	$position = $_POST['position'];
	$department = $_POST['department'];
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$role = $_POST['role'];
	$status = $_POST['status'];
	$now = date('Y-m-d H:i:s');
	
	$sqlCheckUsername = "SELECT username FROM user WHERE username = '$username'";

	$result = mysqli_query($conn, $sqlCheckUsername);

	if (mysqli_num_rows($result) == 0) {
		
		$sql = "INSERT INTO user (id,fullname,position,department,username,password,role,status,created_at,updated_at)
		VALUES (NULL,'$fullname', '$position', '$department', '$username','$password','$role','$status','$now','$now')";

		if (mysqli_query($conn, $sql)) {

				header('location:user.php');
			
		} else {
			//กรณีเกิดข้อผิดพลาด
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
	}else{
		
		$message = "<p class='alert alert-warning'>ขออภัยมีผู้อื่นใช้งาน username นี้แล้ว โปรดใช้ Username อื่น</p>";
	}
	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>User</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<?php include("script/css_script.php")?>
	
	<!-- DataTables -->
	<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	  
</head>
  
<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include("layout/header.php")?>

  <!-- Left side column. contains the sidebar -->
  <?php echo asideAdminMenu('user');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">ตั้งค่าผู้ใช้งานระบบ</h3>
        </div>
        <div class="box-body">

				<div class="col-md-offset-3 col-md-6">

					<div class="card border-primary">
						<div class="card-body">
						
							<?php echo $message?>

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
											$sql = "SELECT * FROM department WHERE status = 1";

												$result = mysqli_query($conn, $sql);

												if (mysqli_num_rows($result) > 0) {
								   
													while ($row = mysqli_fetch_assoc($result)) {

														echo "<option value='".$row['id']."'>".$row['department_name']."</option>";
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
								<div class="form-group">	
									<label>Role</label>
									<select name="role" class="form-control" required>
										<option value="">-- Select Role --</option>
										<option value="admin">--Admin--</option>
										<option value="user">--User--</option>
									</select>
								</div>
								<div class="form-group">
									<label>Status</label>
									<input type="radio" name="status" value="y" checked="checked"> เปิดใช้งาน
									<input type="radio" name="status" value="n"> ระงับการเข้าใช้งานระบบ
								</div>
								<hr>
								<div class="form-group text-right">
									<button type="submit" class="btn btn-primary">Save</button>
									<a href="user.php" class="btn btn-light">Close</a>
								</div>
							</form>

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

</body>
</html>

