<?php
require("config/db.php");

require('function.php');

$message = "";

//ตรวจสอบว่ามีการส่งค่าตัวแปร id มาหรือไม่	
if(isset($_GET['event_id'])){
	
	$id = $_GET['event_id'];
	
	//คำสั่ง SQL ค้นหาข้อมูล user จาก id ที่ได้รับมา
	$sql = "SELECT id,user_id,room_id,title,DATE(start_date) as start_date,DATE(end_date) as end_date, HOUR(TIME(start_date)) as start_hour,MINUTE(start_date) as start_minute,HOUR(TIME(end_date)) as end_hour,MINUTE(end_date) as end_minute,detail,person_use,status FROM events  WHERE id = $id";

	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
		
		$r = mysqli_fetch_assoc($result);
		
		$id = $r['id'];
		$user_id = $r['user_id'];
		$room_id = $r['room_id'];
		$title = $r['title'];
		$detail = $r['detail'];
		$start_date = $r['start_date'];
		$end_date = $r['end_date'];
		$start_hour = $r['start_hour'];
		$start_minute = $r['start_minute'];
		$end_hour = $r['end_hour'];
		$end_minute = $r['end_minute'];
		$person_use = $r['person_use'];
		$status = $r['status'];
		
		$zero1 = $start_hour < 10 ? '0' : '';
		
		$zero2 = $start_minute < 10 ? '0' : '';
		
		$zero3 = $end_hour < 10 ? '0' : '';
		
		$zero4 = $end_minute < 10 ? '0' : '';
		
		$find_start_date = $start_date." ".$zero1.$start_hour.":".$zero2.$start_minute.":00";

		$find_end_date = $end_date." ".$zero3.$end_hour.":".$zero4.$end_minute.":00";
	}
}

//ตรวจสอบว่ามีการส่งค่าตัวแปร update_id มาจากฟอร์มหรือไม่
if(isset($_POST['event_update_id'])){
	
	$start_time = $_POST['start_hour'].':'.$_POST['start_minute'].':00';

	$end_time = $_POST['end_hour'].':'.$_POST['end_minute'].':00';
	
	$event_update_id = $_POST['event_update_id'];
	$update_room_id = $_POST['room_id'];
	$title = $_POST['title'];
	$detail = $_POST['detail'];
	$update_start_date = $_POST['start_date'].' '.$start_time;
	$update_end_date = $_POST['end_date'].' '.$end_time;
	
	$status = $_POST['status'];

	if($find_start_date == $update_start_date && $find_end_date == $update_end_date && $update_room_id = $room_id){
		
		$bookNow['status'] = true;
		
		$message = "";
		
	}else{
		
		$bookNow = checkDupBooking($conn,$update_start_date,$update_end_date,$update_room_id,$event_update_id);
		
		$message = $bookNow['message'];
	}
	
	if($bookNow['status']){

		$sql = "UPDATE events SET room_id='$update_room_id',title='$title',detail='$detail',start_date='$update_start_date',end_date='$update_end_date',status='$status' WHERE id= $event_update_id";

		if (mysqli_query($conn, $sql)) {

			header("location:calendar.php");
			
		} else {
			echo "Error updating record: " . mysqli_error($conn);
		}	
		
	}
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>รายละเอียด</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<?php include("script/css_script.php")?>
	
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
</head>
  
<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php include("layout/header.php")?>

  <!-- =============================================== -->
  <!-- Left side column. contains the sidebar -->
  <?php echo asideMenu('event_add');?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">รายละเอียด</h3>
        </div>
        <div class="box-body">
		
			<div class="col-md-offset-2 col-md-8">

				<div class="card border-primary">
					<div class="card-body">
					
					<?php echo $message ?>
					
						<form method="post">

							<div class="form-group">
								<label>เรื่อง</label>
								<input type="text" class="form-control" name="title" value="<?php echo $title?>" required>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>วันที่เริ่มใช้ห้อง</label>
										<input type="text" class="form-control datepicker" name="start_date" value="<?php echo $start_date?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>เวลา(ชั่วโมง)</label>
											<select name="start_hour" class="form-control">
											<?php
												for($i=0;$i<=23;$i++){
													
													$selected = $start_hour == $i ? 'selected' : '';
													
													$zero = $i < 10 ? '0' : '';
													
													echo "<option value='".$zero.$i."' $selected>$zero$i</option>";
													
												}
											?>
											</select>
									</div>								
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>นาที</label>
											<select name="start_minute" class="form-control">
											<?php
												for($i=0;$i<=59;$i++){
													
													$selected = $start_minute == $i ? 'selected' : '';
													
													$zero = $i < 10 ? '0' : '';
													
													echo "<option value='".$zero.$i."' $selected>$zero$i</option>";
												}
											?>
											</select>
									</div>								
								</div>							
							</div>
							
							
							<div class="row">
								<div class="col-md-6">
								
									<div class="form-group">
										<label>สิ้นสุดการใช้ห้อง</label>
										<input type="text" class="form-control datepicker" name="end_date" value="<?php echo $end_date?>">
										
									</div>								
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label>เวลา(ชั่วโมง)</label>
											<select name="end_hour" class="form-control">
											<?php
												for($i=0;$i<=23;$i++){
													
													$selected = $end_hour == $i ? 'selected' : '';
													
													$zero = $i < 10 ? '0' : '';
													
													echo "<option value='".$zero.$i."' $selected>$zero$i</option>";
												}
											?>
											</select>
									</div>								
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>นาที</label>
											<select name="end_minute" class="form-control">
											<?php
												for($i=0;$i<=59;$i++){
													
													$selected = $end_minute == $i ? 'selected' : '';
													
													$zero = $i < 10 ? '0' : '';
													
													echo "<option value='".$zero.$i."' $selected >$zero$i</option>";
												}
											?>
											</select>
									</div>								
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">	
										<label>ห้องประชุม</label>
										<select name="room_id" class="form-control" required>
											 <option value="">-- เลือกห้อง --</option>
											<?php
													$sql = "SELECT * FROM room WHERE status = 1";

														$result = mysqli_query($conn, $sql);

														if (mysqli_num_rows($result) > 0) {
										   
															while ($row = mysqli_fetch_assoc($result)) {
																
																if($row['id'] == $room_id){
																	
																	echo "<option value='".$row['id']."' selected>".$row['room_name']."</option>";
																	
																}else{
																	
																	echo "<option value='".$row['id']."'>".$row['room_name']."</option>";
																}

															}
														} else {
															echo "0 results";
														}
											?>
										   

										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>จำนวนผู้เข้าประชุม</label>
										<input type="text" class="form-control" name="person_use" value="<?php echo $person_use?>">
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label>รายละเอียด</label>
								<textarea rows="3" class="form-control" name="detail"><?php echo $detail?></textarea>
							</div>
							
							<?php
								if ($_SESSION["u_role"] == 'admin') {
							?>
											
							<div class="form-check form-check-inline">
							  <label class="form-check-label">
								<input class="form-check-input" type="radio" name="status" value="wait" <?php echo $status == 'wait' ? 'checked' : ''?> > รออนุมัติ
							  </label>
							  <label class="form-check-label">
								<input class="form-check-input" type="radio" name="status" value="approve" <?php echo $status == 'approve' ? 'checked' : ''?> > อนุมัติ
							  </label>
							  <label class="form-check-label">
								<input class="form-check-input" type="radio" name="status" value="not_approved" <?php echo $status == 'not_approved' ? 'checked' : ''?>> ไม่อนุมัติ
							  </label>

							  	<label class="form-check-label">
								<input class="form-check-input" type="radio" name="status" value="cancel" <?php echo $status == 'cancel' ? 'checked' : ''?>> ยกเลิก
							  </label>
							</div>				
							
							<?php
								}else{
									echo "<input type='hidden' name='status' value='wait'>";
								}
							?>
						
							<input type="hidden" name="event_update_id" value="<?php echo $id ?>">

							<hr>
							<div class="form-group text-right">
								<button type="submit" class="btn bg-navy btn-flat">บันทึก</button>
								<a href="calendar.php" class="btn btn-default btn-flat">ปิด</a>
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

<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>
	$(function(){

		//Date picker
		$('.datepicker').datepicker({
		   format: 'yyyy-mm-dd',
		   autoclose: true
		});
	});
</script>

</body>
</html>
