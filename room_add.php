<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();
 
 if(isset($_POST['room_name']) && isset($_POST['room_detail'])){
	 
	$uploadOk = 1;
	 
	if(isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0){

		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			
			$new_file_name = date("ymdhis").'.'.$imageFileType;
			
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target_dir.$new_file_name)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
				exit();
			}
		}
	}
		

	$room_name = $_POST['room_name'];
	
	$room_detail = $_POST['room_detail'];
	
	if(!empty($new_file_name)){
		
		$image = $new_file_name;
	
	}else{
		
		$image = "";
	}

	$now = date('Y-m-d H:i:s');
	
	//คำสั่ง SQL เพิ่มข้อมูลในฐานช้อมูล
	$sql = "INSERT INTO room (id,room_name,room_detail,room_image,created_at,updated_at)
	VALUES (NULL,'$room_name','$room_detail','$image','$now','$now')";

	if (mysqli_query($conn, $sql)) {
		
		if($uploadOk > 0){
			header("location:room.php");
		}
		
	} else {
		
		//กรณีเกิดข้อผิดพลาด
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
		
 }

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Room</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<?php include("script/css_script.php")?>
	
</head>
  
<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include("layout/header.php")?>

  <!-- Left side column. contains the sidebar -->
  <?php echo asideAdminMenu('room');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">เพิ่มข้อมูลห้องประชุม</h3>
        </div>
        <div class="box-body">
		
			<div class="col-md-offset-3 col-md-6">

				<div class="card border-primary">
					<div class="card-body">

						<form method="post" enctype="multipart/form-data">

							<div class="form-group">
								<label>ชื่อห้องประชุม</label>
								<input type="text" class="form-control" name="room_name" required>
							</div>

							<div class="form-group">
								<label>รายละเอียด</label>
								<input type="text" class="form-control" name="room_detail">
							</div>
							<div class="form-group">
								<label>รูปภาพ</label>
								<input type="file" name="fileToUpload" id="fileToUpload">
							</div>

							<div class="form-group text-right">
								<button type="submit" class="btn btn-primary">บันทึก</button>
								<a href="room.php" class="btn btn-default">ปิด</a>
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
