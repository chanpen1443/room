<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();

	$show_status = array(
		'n' => 0,
		'y' => 0
	);

	$sql1 = "SELECT status,COUNT(*) as total FROM `user` WHERE status <> 'delete' GROUP BY status ";

	$result1 = mysqli_query($conn, $sql1);

	if (mysqli_num_rows($result1) > 0) {

		while($row = mysqli_fetch_assoc($result1)) {
			
			$show_status[$row['status']] = $row['total'];
		}
	}
	
	$total = $show_status['y'] + $show_status['n'];
	
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
		
			<?php
				if(isset($_GET['msg'])){
					if($_GET['msg'] == 'success'){
						echo '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<p><i class="icon fa fa-check"></i>บันทึกข้อมูลเรียบร้อยแล้ว!</p>
						  </div>';
					}
					
					if($_GET['msg'] == 'danger'){
						echo '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<p><i class="icon fa fa-check"></i>ลบข้อมูลเรียบร้อยแล้ว!</p>
						  </div>';
					}
				}
			?>
		
			<p>
				<a href="user_add.php" class="btn bg-navy btn-flat"><i class="fa fa-plus"></i> เพิ่มผู้ใช้งาน</a>
			</p>

			<div style="margin-bottom:10px;">
				<a href="user.php?status=y">ใช้งาน <span class=" badge bg-green"><?php echo $show_status['y']?></span></a>  &nbsp;&nbsp; 
				<a href="user.php?status=n">ระงับการใช้งาน <span class="badge bg-red"><?php echo $show_status['n']?></span></a> &nbsp;&nbsp; 
				<a href="user.php">ทั้งหมด <span class="badge bg-blue"><?php echo $total?></span></a> &nbsp;&nbsp; 
			</div>
			
			<table id="tb-user" class="table table-responsive" >
				<thead class="thead-dark">
					<tr class="active">
						<th scope="col" width="5%">#</th>
						<th scope="col" width="30%">ชื่อ - นามสกุล</th>
						<th scope="col" width="20%">ตำแหน่ง</th>
						<th scope="col" width="20%">แผนก</th>
						<th scope="col" width="15%">สิทธิ์</th>
						<th scope="col" width="5%">แก้ไข</th>
						<th scope="col" width="5%">ลบ</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if(isset($_GET['status']) && ($_GET['status'] == 'y' || $_GET['status'] == 'n')){
						
						$findStatus = $_GET['status'];
						
						$sql = "SELECT user.id,fullname,position,role,user.status,department_name FROM user INNER JOIN department ON department.id = user.department  WHERE user.status = '$findStatus'";
					
					}else{

						$sql = "SELECT user.id,fullname,position,role,user.status,department_name FROM user INNER JOIN department ON department.id = user.department
							WHERE user.status <> 'delete'
						";
					}

					$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) > 0) {
						$i = 1;
						while($row = mysqli_fetch_assoc($result)) {
							
							echo "<tr>";
								echo "<td scope='row'>".$i."</td>";
								echo "<td>".$row['fullname']."</td>";
								echo "<td>".$row['position']."</td>";
								echo "<td>".$row['department_name']."</td>";
								echo "<td>".$row['role']."</td>";
								//echo "<td>".userStatus($row['status'])."</td>";
								echo "<td><a href='user_update.php?id=".$row['id']."' class='btn btn-warning btn-sm'>แก้ไข</a></td>";
								echo "<td><a href='user_delete.php?id=".$row['id']."' onclick='return confirm(\"คุณต้องการลบข้อมูลหรือไม่\");'  class='btn btn-danger btn-sm'>ลบ</a></td>";
							echo "</tr>";
							$i++;
						}
						
					} else {
						echo "0 results";
					}

					mysqli_close($conn);
				?>
				</tbody>
			</table>
				
				
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

<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script>
	$(function(){
	    $('#tb-user').DataTable();
		
		setTimeout(function(){ $('.close').trigger('click') }, 5000);
	});
</script>

</body>
</html>