<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<link rel="stylesheet" href="resourceVNT/css/bootstrap.css">
	<link rel="stylesheet" href="resourceVNT/css/login.css">
	<title>Login</title>
</head>
<body>
	<section class="content" style="background-image: url({{asset('/resourceVNT/images/background/4.jpg')}});">
		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>	
				<div class="col-md-4 form-login tada">
					<div class="title">
						<h3 class="text-center">Đăng ký</h3>
					</div>
					<div class="body">
						<form action="" method="post">
							<input type="hidden" name="csrf-token" content="{{ csrf_token() }}">
							
								
							
							<div class="login">
								<input type="text" placeholder="Tài khoản" name="username">
								<input type="password" placeholder="Mật khẩu" name="password">
								<input type="password" placeholder="Xác nhận mật khẩu" name="passwordC">
								<button class="btn btn-info float-right btnlogin" type="submit" name="btnregister">Đăng ký</button>
							</div>
							<div class="register text-center" style="border: none;">
								<h5 class="text-center">Bạn đã có tài khoản</h5>
								<a href="{{route('loginW')}}" class="btn btn-success btnregister">Login</a>
							</div>
						</form>
					</div>
				</div>	
				<div class="col-md-4"></div>	
			</div>
		</div>
	</section>

	<script type="text/javascript" src="resourceVNT/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="resourceVNT/js/bootstrap.js"></script>
	<script src="resourceVNT/js/fontawesome-all.min.js" type="text/javascript"></script>
</body>
</html>