<?php

require("config/db.php"); //นำเข้าไฟล์ db.php สำหรับเชื่อมต่อฐานข้อมูล

require("function.php"); //เรียกใช้งานไฟล์ function.php

auth(); //ตรวจสอบว่าผู้ใช้งาน Login เข้าสู่ระบบแล้วหรือไม่

if (isset($_GET['find_event_id'])) {

    if (!empty($_GET['find_event_id'])) {

        $event_id = $_GET['find_event_id'];

        //Find One Event
        $sql = "SELECT events.id,user_id,title,DATE(start_date) as start_date,DATE(end_date) as end_date,
		TIME(start_date) as start_time , TIME(end_date) as end_time,detail,person_use,events.status,
				room.room_name,user.fullname,user.department,equipment,room.room_image,department.department_name
				FROM events 
				INNER JOIN room ON room.id = events.room_id 
				INNER JOIN user ON user.id = events.user_id 
                LEFT JOIN department On user.department = department.id
                WHERE events.id = $event_id";

        $result = mysqli_query($conn, $sql);

        $json = [];

        $allowUpdate = false;

        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            if ($_SESSION['u_role'] == 'admin' || ($_SESSION['u_id'] == $row['user_id'] && $row['status'] == 'wait')) {

                $allowUpdate = true;
            } else {

                $allowUpdate = false;
            }

            $json = [
                'id' => $row['id'],
                'title' => $row['title'],
                'start' => $row['start_date'],
                'end' => date('Y-m-d',strtotime("+1 day",strtotime($row['end_date']))),
                'booking_start' => dateTimeThai($row['start_date'] . " " . $row['start_time']),
                'booking_end' => dateTimeThai($row['end_date'] . " " . $row['end_time']),
                'description' => $row['detail'],
                'room_name' => $row['room_name'],
                'room_image' => $row['room_image'],
                'fullname' => $row['fullname'],
                'department' => $row['department_name'],
                'person_use' => $row['person_use'],
                'status' => eventStatusName($row['status']),
                'textColor' => '#FFF',
                'borderColor' => '#FFF',
                'backgroundColor' => eventStatusColor($row['status']),
                'className' => 'calendarText',
                'allowUpdate' => $allowUpdate
            ];
        }

        echo $data = json_encode($json);
    }
} else {

    //Find All Events
    $sql = "SELECT events.id,user_id,title,DATE(start_date) as start_date,DATE(end_date) as end_date,status FROM events";

    $result = mysqli_query($conn, $sql);

    $json = [];

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {

            $json[] = [
                'id' => $row['id'],
                'title' => $row['title'].' ('.eventStatusName($row['status']).')',
                'start' => $row['start_date'],
                'end' => date('Y-m-d',strtotime("+1 day",strtotime($row['end_date']))),
                'status' => eventStatusName($row['status']),
                'textColor' => '#FFF',
                'borderColor' => '#FFF',
                'backgroundColor' => eventStatusColor($row['status']),
                'className' => 'calendarText',
            ];
        }
    }

    echo $data = json_encode($json);
}

mysqli_close($conn);
