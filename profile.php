<?php
require("config/db.php");

require("function.php");

auth();
 
	$message = "";
 
	$sql = "SELECT * FROM user WHERE id = ".$_SESSION['u_id']."";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {

		$row = mysqli_fetch_assoc($result);
		$fullname = $row['fullname'];
		$position = $row['position'];
		$department = $row['department'];
		$username = $row['username'];
		$id = $row['id'];
		
	} else {
		exit();
	}
	
	if(isset($_POST['username'])){
		
		$post_username = $_POST['username'];
		$post_fullname = $_POST['fullname'];
		$post_position = $_POST['position'];
		$post_password =  $_POST['password'];
		
			$sql = "SELECT * FROM user WHERE username = '$post_username' AND id <> $id";

			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) == 0) {
				
					if(empty($post_password)){
						
						$sql = "UPDATE user SET username = '$post_username',fullname='$post_fullname',position='$post_position' WHERE id=$id";
					
					}else{
						
						$p_possword = md5($post_password);
						
						$sql = "UPDATE user SET username = '$post_username',password = '$p_possword',fullname='$post_fullname',position='$post_position' WHERE id=$id";
					}


					if (mysqli_query($conn, $sql)) {
						
						$message = "<p class='alert alert-success'>บันทึกข้อมูลเรียบร้อย</p>";
						
					} else {
						//กรณีเกิดข้อผิดพลาด
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
  <title>Profile</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<?php include("script/css_script.php")?>
	
</head>
  
<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include("layout/header.php")?>

  <!-- Left side column. contains the sidebar -->
  <?php echo asideMenu('profile');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Profile</h3>
        </div>
        <div class="box-body">
		
			<div class="col-md-offset-3 col-md-6">
				<?php echo $message?>
				<form method="post">
					<div class="form-group">
						<label>ชื่อ - นามสกุล</label>
						 <input type="text" name="fullname" class="form-control" value="<?php echo $fullname?>" required>
					</div>
					<div class="form-group">
						<label>ตำแหน่ง</label>
						 <input type="text" name="position" class="form-control" value="<?php echo $position?>">
					</div>

					<div class="form-group">
						<label>Username</label>
						 <input type="text" name="username" class="form-control" value="<?php echo $username?>" required>
					</div>
					
					<div class="form-group">
						<label>Password</label>
						 <input type="password" name="password" class="form-control">
					</div>
					
					<div class="form-group">
						 <input type="submit" value="Save Change" class="btn btn-primary">
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