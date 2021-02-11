<?php
require("config/db.php");

require("function.php");

checkAuthAdmin();
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

        <!-- DataTables -->
        <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

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
                            <h3 class="box-title">ตั้งค่า หน่วยงาน / แผนก</h3>
                        </div>
                        <div class="box-body">

                            <p>
                                <a href="department_add.php" class="btn bg-navy btn-flat">
									<i class="fa fa-plus"></i> เพิ่มข้อมูล
								</a>
                            </p>

                            <table id="tb-department" class="table table-responsive">
                                <thead>
                                    <tr class="active">
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="75%">หน่วยงาน / แผนก</th>
                                        <th scope="col" width="5%">แก้ไข</th>
                                        <th scope="col" width="5%">ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM department WHERE status = 1";

                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {

                                            echo "<tr>";
                                            echo "<td >" . $i . "</td>";
                                            echo "<td>" . $row['department_name'] . "</td>";
                                            echo "<td><a href='department_update.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>แก้ไข</a></td>";
                                            echo "<td><a href='department_delete.php?id=" . $row['id'] . "' onclick='return confirm(\"คุณต้องการลบข้อมูลหรือไม่\");'  class='btn btn-danger btn-sm'>ลบ</a></td>";
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

		<?php include("layout/footer.php") ?>

            <div class="control-sidebar-bg"></div>

        </div>
        <!-- ./wrapper -->

		<?php include("script/js_script.php") ?>

        <!-- DataTables -->
        <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

        <script>
            $(function () {
                $('#tb-department').DataTable()
            });
        </script>

    </body>
</html>