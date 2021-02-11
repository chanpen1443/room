<?php
require("config/db.php");

require('function.php');

$message = "";

if (isset($_POST['title']) && isset($_POST['room_id'])) {

    $start_time = $_POST['start_hour'] . ':' . $_POST['start_minute'] . ':00';

    $end_time = $_POST['end_hour'] . ':' . $_POST['end_minute'] . ':00';

    $user_id = $_SESSION['u_id'];
    $room_id = $_POST['room_id'];
    $title = $_POST['title'];
    $detail = $_POST['detail'];
    $start_date = $_POST['start_date'] . ' ' . $start_time;
    $end_date = $_POST['end_date'] . ' ' . $end_time;
    $person_use = $_POST['person_use'];
    $status = 'wait';
    $now = date('Y-m-d H:i:s');

    $bookNow = checkDupBooking($conn, $start_date, $end_date, $room_id);

    $message = $bookNow['message'];

    if ($bookNow['status']) {

        $sql = "INSERT INTO events (id,user_id,room_id,title,detail,start_date,end_date,person_use,equipment,status,created_at,updated_at)
			VALUES (NULL,$user_id, $room_id, '$title', '$detail','$start_date','$end_date','$person_use','$equipment','$status','$now','$now')";

        if (mysqli_query($conn, $sql)) {

            header('location:calendar.php');
        } else {

            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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

        <?php include("script/css_script.php") ?>

        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

    </head>

    <body class="hold-transition skin-green sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

            <?php include("layout/header.php") ?>

            <!-- =============================================== -->
            <!-- Left side column. contains the sidebar -->
            <?php echo asideMenu('event_add'); ?>


            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Main content -->
                <section class="content">

                    <div class="col-md-offset-2 col-md-8">

                        <!-- Default box -->
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">จองห้องประชุม</h3>
                            </div>
                            <div class="box-body">

                                <div class="card border-primary">
                                    <div class="card-body">

                                        <?php echo $message ?>

                                        <form method="post">

                                            <div class="form-group">
                                                <label>เรื่อง</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>วันที่เริ่มใช้ห้อง</label>
                                                        <input type="text" class="form-control datepicker" name="start_date" value="<?php echo date("Y-m-d") ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>เวลาเริ่มใช้</label>
                                                        <select name="start_hour" class="form-control">
                                                            <?php
                                                            for ($i = 0; $i <= 23; $i++) {
                                                                $zero = $i < 10 ? '0' : '';
                                                                echo "<option value='" . $zero . $i . "'>$zero$i</option>";
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
                                                            for ($i = 0; $i <= 59; $i++) {
                                                                $zero = $i < 10 ? '0' : '';
                                                                echo "<option value='" . $zero . $i . "'>$zero$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>								
                                                </div>							
                                            </div>


                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label>วันที่สิ้นสุดการใช้ห้อง</label>
                                                        <input type="text" class="form-control datepicker" name="end_date" value="<?php echo date("Y-m-d") ?>">

                                                    </div>								
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>เวลาสิ้นสุด</label>
                                                        <select name="end_hour" class="form-control">
                                                            <?php
                                                            for ($i = 0; $i <= 23; $i++) {
                                                                $zero = $i < 10 ? '0' : '';
                                                                echo "<option value='" . $zero . $i . "'>$zero$i</option>";
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
                                                            for ($i = 0; $i <= 59; $i++) {
                                                                $zero = $i < 10 ? '0' : '';
                                                                echo "<option value='" . $zero . $i . "'>$zero$i</option>";
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

                                                                    echo "<option value='" . $row['id'] . "'>" . $row['room_name'] . "</option>";
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
                                                        <input type="text" class="form-control" name="person_use">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>รายละเอียด</label>
                                                <textarea rows="3" class="form-control" name="detail"></textarea>
                                            </div>

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

            <?php include("layout/footer.php") ?>

            <div class="control-sidebar-bg"></div>

        </div>
        <!-- ./wrapper -->

        <?php include("script/js_script.php") ?>

        <!-- bootstrap datepicker -->
        <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

        <script>
            $(function () {

                //Date picker
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });
        </script>

    </body>
</html>
