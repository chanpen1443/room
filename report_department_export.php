<?php

	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Asia/Bangkok');
	
	/** Include PHPExcel */
	require('Classes/PHPExcel.php');
	
	require("config/db.php");


	$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d");

	$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d");

	$department_id = !empty($_GET['department_id']) ? $_GET['department_id'] : 1;

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()
			 ->setCreator("Maarten Balliauw")
			 ->setLastModifiedBy("Maarten Balliauw")
			 ->setTitle("Office 2007 XLSX Test Document")
			 ->setSubject("Office 2007 XLSX Test Document")
			 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Test result file");
			 
		$i = 2;
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A1",'วันที่เริ่ม')
				->setCellValue("B1",'เวลาเริ่ม')
				->setCellValue("C1",'วันที่สิ้นสุด')
				->setCellValue("D1",'เวลาสิ้นสุด')
				->setCellValue("E1",'เรื่อง')
				->setCellValue("F1",'ห้องประชุม')
				->setCellValue("G1",'หน่วยงาน')
				->setCellValue("H1",'สถานะ');

			$sql = "SELECT events.id,DATE(start_date) as s_date,DATE(end_date) as e_date,TIME_FORMAT(start_date, '%h:%i') as start_time,
					TIME_FORMAT(end_date, '%h:%i')  as end_time,title,room_name,department_name,events.status FROM `events` 
					INNER JOIN room ON room.id = events.room_id
					INNER JOIN user ON user.id = events.user_id
					INNER JOIN department ON department.id = user.department
					WHERE DATE(start_date) BETWEEN '$start_date' AND '$end_date' AND department.id = $department_id";

			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
				
				$status = "";
				
				while($row = mysqli_fetch_assoc($result)) {
					
					if($row['status'] == 'wait'){
						$status = "รออนุมัติ";
					}else if($row['status'] == 'approve'){
						$status = 'อนุมัติ';
					}else if($row['status'] == 'not_approved'){
						$status = 'ไม่อนุมัติ';
					}else if($row['status'] == 'cancel'){
						$status = 'ยกเลิก';
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$i",$row['s_date'])
						->setCellValue("B$i",$row['start_time'])
						->setCellValue("C$i",$row['e_date'])
						->setCellValue("D$i",$row['end_time'])
						->setCellValue("E$i",$row['title'])
						->setCellValue("F$i",$row['room_name'])
						->setCellValue("G$i",$row['department_name'])
						->setCellValue("H$i",$status);

					$i++;
				}
				
			}
			
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Report');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="report.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit();
