<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();
 
 $message = "";
 
 	if(isset($_GET['id'])){

		$id = $_GET['id'];
		
		//คำสั่ง SQL ค้นหาข้อมูล user จาก id ที่ได้รับมา
		$sql = "SELECT * FROM user WHERE id = $id";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
		
			$r = mysqli_fetch_assoc($result);
			
			$id = $r['id'];
			$fullname = $r['fullname'];
			$position = $r['position'];
			$department = $r['department'];
			$username = $r['username'];
			$role = $r['role'];
			$status = $r['status'];
			
		}else {
				echo "ไม่พบข้อมูล";
				exit();
		}
		
	}else{
		
		echo "ไม่พบข้อมูล";
		exit();
	}

	//ตรวจสอบว่ามีการส่งค่าตัวแปร update_id มาจากฟอร์มหรือไม่
	if(isset($_POST['update_id'])){
		
		$update_id = $_POST['update_id'];
		$fullname = $_POST['fullname'];
		$position = $_POST['position'];
		$department = $_POST['department'];
		$username = $_POST['username'];
		$role = $_POST['role'];
		$password = $_POST['password'];
		$status = $_POST['status'];
		
		$sqlCheckUsername = "SELECT username FROM user WHERE username = '$username' AND id <> $update_id";

		$result = mysqli_query($conn, $sqlCheckUsername);

		if (mysqli_num_rows($result) == 0) {
			
			//ตรวจสอบว่าได้มีการส่งค่า password ที่ต้องการเปลี่ยนมาหรือไม่
			if(!empty($password)){
				
				$set_password = md5($password);
				
				$password = ",password='$set_password'";
				
			}else{
				$password = null;
			}
			
			$sql = "UPDATE user SET fullname='$fullname',position='$position',department='$department',username='$username',role='$role',status='$status' $password WHERE id= $update_id";

			if (mysqli_query($conn, $sql)) {

				header("location:user.php?msg=success");
				
			} else {
				echo "Error updating record: " . mysqli_error($conn);
			}			
			

		}else{
				
			$message = "<p class='alert alert-warning'>ขออภัยมีผู้อื่นใช้งาน username นี้แล้ว</p>";
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
			
			<?php echo $message?>
		
				<form method="POST">

					<div class="form-group">
						<label>ชื่อ - นามสกุล</label>
						<input type="text" name="fullname" class="form-control" value="<?php echo $fullname ?>">
					</div>
					<div class="form-group">
						<label>ตำแหน่ง</label>
						<input type="text" name="position" class="form-control" value="<?php echo $position ?>">
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
						<input type="text" name="username" class="form-control" value="<?php echo $username ?>" required>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" name="password" class="form-control" placeholder="เว้นว่างไว้หากต้องการใช้รหัสผ่านเดิม">
					</div>
					<div class="form-group">
						<select name="role" class="form-control">
							<option value="admin" <?php echo $role == 'admin' ? 'selected' : '' ?> >--Admin--</option>
							<option value="user"  <?php echo $role == 'user' ? 'selected' : '' ?>>--User--</option>
						</select>                
					</div>      

					<div class="form-group">
						<label>Status</label>
						<input type="radio" name="status" value="y" <?php echo $status == 'y' ? 'checked' : ''?>> เปิดใช้งาน
						<input type="radio" name="status" value="n" <?php echo $status == 'n' ? 'checked' : ''?>> ระงับการเข้าใช้งานระบบ
					</div>					

					<input type="hidden" name="update_id" value="<?php echo $id ?>">

					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Save</button>
						<a href="user.php" class="btn btn-light">Close</a>
					</div>
				</form>
				
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