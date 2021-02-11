<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();
 
 	if(isset($_GET['id'])){
	
		$id = $_GET['id'];
		
		//คำสั่ง SQL ค้นหาข้อมูล user จาก id ที่ได้รับมา
		$sql = "SELECT * FROM room WHERE id = $id";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			
				$r = mysqli_fetch_assoc($result);
				
				$id = $r['id'];
				$room_name = $r['room_name'];
				$room_detail = $r['room_detail'];
				$room_image = $r['room_image'];
		}else{
			
			exit();
		}
	
	}else{
		exit();
	}
 
	if(isset($_POST['room_update_id'])){
	 
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
		
		$room_update_id = $_POST['room_update_id'];
		$room_name = $_POST['room_name'];
		$room_detail = $_POST['room_detail'];
		$old_image = $_POST['old_image'];
		
		if(!empty($new_file_name)){
			
			$image = $new_file_name;
		
		}else{
			
			$image = $old_image;
		}
		
		$sql = "UPDATE room SET room_name='$room_name',room_detail='$room_detail',room_image='$image' WHERE id= $room_update_id";

		if (mysqli_query($conn, $sql)) {
			
			if($uploadOk > 0){
				header("location:room.php");
			}

		} else {
			echo "Error updating record: " . mysqli_error($conn);
			exit();
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
          <h3 class="box-title">แก้ไขข้อมูลห้องประชุม</h3>
        </div>
        <div class="box-body">
			
			<div class="col-md-offset-3 col-md-6">
			
				<form method="post" enctype="multipart/form-data">

					<div class="form-group">
						<label>ชื่อห้องประชุม</label>
						<input type="text" name="room_name" class="form-control" value="<?php echo $room_name ?>">
					</div>
					<div class="form-group">
						<label>รายละเอียด</label>
						<input type="text" name="room_detail" class="form-control" value="<?php echo $room_detail ?>">
					</div>
					<div class="form-group">
						<label>รูปภาพ</label>
						<input type="file" name="fileToUpload" id="fileToUpload">

					</div>
					<?php 
						if(!empty($room_image)){
							echo "<img src='uploads/".$room_image."' class='img-thumbnail' width='250' height='250'>";
						}
					?>

					<input type="hidden" name="room_update_id" value="<?php echo $id ?>">
					
					<input type="hidden" name="old_image" value="<?php echo $room_image ?>">

					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">บันทึก</button>
						<a href="room.php" class="btn btn-default">ลบ</a>
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