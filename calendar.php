<?php

require('function.php'); //เรียกใช้งานไฟล์ function.php

auth(); //ตรวจสอบว่าผู้ใช้งาน Login เข้าสู่ระบบแล้วหรือไม่

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Calendar</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php include("script/css_script.php"); //เรียกใช้งานไฟล์  css_script.php ?>

        <!-- fullCalendar -->
        <link rel="stylesheet" href="bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
        <style>
            .calendarText{
                padding:2px;
                cursor:pointer;
                font-size:14px;
            }

            .fc-day-grid-event > .fc-content {
                white-space: normal;
            }
        </style>  
    </head>

    <body class="hold-transition skin-green sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

            <?php include("layout/header.php"); //เรียกใช้งานไฟล์   header.php?>

            <?php echo asideMenu('calendar'); //เรียกใช้งาน function asideMenu?>

            <div class="content-wrapper">

                <section class="content-header">
                    <h1>
                      ปฏิทินการใช้ห้องประชุม

                    </h1>
                </section>

                <section class="content">

                    <div class="box">
                        <div class="box-header with-border">
                           <a href="#" class="btn btn-sm" style="background: #efc443;color:#FFF;">
                           รออนุมัติ</a>
                           <a href="#" class="btn btn-sm" style="background: #7cbc87;color:#FFF;">
                            อนุมัติ</a>
                          <a href="#" class="btn btn-sm" style="background: #728387;color:#FFF;">
                           ไม่อนุมัติ</a>
                           <a href="#" class="btn btn-sm" style="background: #ef5f43;color:#FFF;">
                           ยกเลิก</a>
                        </div>
                        <div class="box-body">

                            <div id='calendar'></div>

                            <div class="modal fade" id="fullCalModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">รายละเอียด</h4>
                                        </div>
                                        <div class="modal-body">

                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="20%">ผู้บันทึกข้อมูล</th>
                                                    <td id="fullname"></td>
                                                </tr>
                                                <tr>
                                                    <th>หน่วยงาน</th>
                                                    <td id="department"></td>
                                                </tr>
                                                <tr>
                                                    <th >เรื่อง</th>
                                                    <td id="title"></td>
                                                </tr>
                                                <tr>
                                                    <th>เริ่มใช้ห้อง</th>
                                                    <td id="booking_start"></td>
                                                </tr>
                                                <tr>
                                                    <th>สิ้นสุดการใช้ห้อง</th>
                                                    <td id="booking_end"></td>
                                                </tr>
                                                <tr>
                                                    <th>รายละเอียด</th>
                                                    <td id="description"></td>
                                                </tr>
                                                <tr>
                                                    <th>จำนวนผู้เข้าประชุม</th>
                                                    <td id="person_use"></td>
                                                </tr>
                                                <tr>
                                                    <th>ชื่อห้องประชุม</th>
                                                    <td  id="room_name"></td>
                                                </tr>
                                                <tr>
                                                    <th>ภาพห้องประชุม</th>
                                                    <td  id="room_image"></td>
                                                </tr>
                                                <!--<tr>
                                                    <th>อุปกรณ์เพิ่มเติม</th>
                                                    <td id="equipment"></td>
                                                </tr>-->
                                                <tr>
                                                    <th>สถานะ</th>
                                                    <td id="status"></td>
                                                </tr>
                                            </table>				  

                                        </div>
                                        <div class="modal-footer">
											<a href="#" class="btn btn-info" id="print" target="_blank">พิมพ์</a>
                                            <a href="#" class="btn btn-primary" id="update_event">แก้ไขข้อมูล</a>
                                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">ปิด</button>

                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
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

        <?php include("script/js_script.php"); // นำเข้าไฟล์ js_script.php?>

        <!--เรียกใช้งาน Library fullCalendar สำหรับแสดงปฏิทิน -->
        <script src="bower_components/moment/moment.js"></script>
        <script src="bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
        <script src="bower_components/fullcalendar/dist/locale/th.js"></script>
        <script>

            $(document).ready(function () {

                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var today = now.getFullYear() + "-" + (month) + "-" + (day);

                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listWeek'
                    },
                    defaultDate: today,
					locale:'th',
                    editable: false,
                    events: {
                        url: 'calendar_json.php',
                        error: function () {
                            $('#script-warning').show();
                        }
                    },
                    //eventColor: '#378006',
                    eventClick: function (event) {

                        $.ajax({
                            url: 'calendar_json.php?find_event_id=' + event.id,
                            dataType: 'json',
                            success: function (data) {

                                $('#fullCalModal').modal();

                                $('#title').html(data.title);
                                $('#description').html(data.description);

                                $('#booking_start').html(data.booking_start);

                                $('#booking_end').html(data.booking_end);

                                $('#equipment').html(data.equipment);
                                $('#person_use').html(data.person_use);
                                $('#room_name').html(data.room_name);
                                $('#room_image').html('<img src="uploads/' + data.room_image + '" class="img-thumbnail" width="280" height="280">');
                                $('#fullname').html(data.fullname);
                                $('#department').html(data.department);
                                $('#status').html(data.status);

                                if (data.allowUpdate) {

                                    $('#update_event').show();

                                    $('#update_event').attr('href', 'event_update.php?event_id=' + data.id);

                                } else {

                                    $('#update_event').hide();
                                }
								
								 $('#print').attr('href', '_print.php?event_id=' + data.id);
                            }
                        });

                    },
                    loading: function (bool) {
                        $('#loading').toggle(bool);
                    },
                });
            });

        </script>

    </body>
</html>
