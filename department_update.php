<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    //คำสั่ง SQL ค้นหาข้อมูล user จาก id ที่ได้รับมา
    $sql = "SELECT * FROM department WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $r = mysqli_fetch_assoc($result);

        $id = $r['id'];
        $department_name = $r['department_name'];
    } else {
        echo "ไม่พบข้อมูล";
        exit();
    }
} else {

    echo "ไม่พบข้อมูล";
    exit();
}

//ตรวจสอบว่ามีการส่งค่าตัวแปร update_id มาจากฟอร์มหรือไม่
if (isset($_POST['update_id'])) {

    $update_id = $_POST['update_id'];

    $department_name = $_POST['department_name'];

    $sql = "UPDATE department SET department_name='$department_name' WHERE id= $update_id";

    if (mysqli_query($conn, $sql)) {

        header("location:department.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Department</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<?php include("script/css_script.php") ?>

    </head>

    <body class="hold-transition skin-green sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

<?php include("layout/header.php") ?>

            <!-- Left side column. contains the sidebar -->
            <?php echo asideAdminMenu('department'); ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Main content -->
                <section class="content">

                    <!-- Default box -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">แก้ไขข้อมูล หน่วยงาน / แผนก</h3>
                        </div>
                        <div class="box-body">


                            <div class="col-md-offset-3 col-md-6">

                                <form method="POST">


                                    <div class="form-group">
                                        <label>หน่วยงาน /แผนก </label>
                                        <input type="text" name="department_name" class="form-control" value="<?php echo $department_name ?>" required>
                                    </div>

                                    <input type="hidden" name="update_id" value="<?php echo $id ?>">

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                        <a href="department.php" class="btn btn-default">ปิด</a>
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

<?php include("layout/footer.php") ?>

            <div class="control-sidebar-bg"></div>

        </div>
        <!-- ./wrapper -->

<?php include("script/js_script.php") ?>

    </body>
</html>