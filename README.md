# room

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
</head>
<body class="hold-transition login-page">
	<div class="login-box">
	  <div class="login-logo">
	   <b>จองห้องสอน</b>&nbsp;ออนไลน์
	  </div>
	  <!-- /.login-logo -->
	  <div class="login-box-body">
	  <br>
		  		  
			<form action="login.php" method="post">
			  <div class="form-group has-feedback">
				<input type="text" class="form-control" name="username" placeholder="Username">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			  </div>
			  <div class="form-group has-feedback">
				<input type="password" class="form-control" name="password" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			  </div>
			  <div class="row">
				<!-- /.col -->
				<div class="col-xs-12">
				  <button type="submit" class="btn btn-primary btn-success btn-flat btn-block">Sign In</button>
				</div>
				<!-- /.col -->
			  </div>
			</form>
			<br>
			<a href="register.php">หากยังไม่มีบัญชีผู้ใช้งาน ลงทะเบียนเข้าสู่ระบบ</a>

	  </div>
	  <!-- /.login-box-body -->
	</div>

</body>
</html>
