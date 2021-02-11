<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();

if (isset($_POST['department_name'])) {

    $department_name = $_POST['department_name'];

    $sql = "INSERT INTO department (id,department_name) VALUES (NULL,'$department_name')";

    if (mysqli_query($conn, $sql)) {

        header('location:department.php');
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
                            <h3 class="box-title">เพิ่มข้อมูล  หน่วยงาน /  แผนก</h3>
                        </div>
                        <div class="box-body">

                            <div class="col-md-offset-3 col-md-6">

                                <div class="card border-primary">
                                    <div class="card-body">

                                        <form method="post">

                                            <div class="form-group">
                                                <label>หน่วยงาน /แผนก</label>
                                                <input type="text" class="form-control" name="department_name">
                                            </div>

                                            <hr>
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                                <a href="department.php" class="btn btn-default">ปิด</a>
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

            <?php include("layout/footer.php") ?>

            <div class="control-sidebar-bg"></div>

        </div>
        <!-- ./wrapper -->

        <?php include("script/js_script.php") ?>

    </body>
</html>

