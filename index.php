<?php
include('includes/config.php');
$reqErr = $loginErr = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (!empty($_POST['txtUsername']) && !empty($_POST['txtPassword']) && isset($_POST['login_type'])) {
		session_start();
		$username = $_POST['txtUsername'];
		$password = $_POST['txtPassword'];
		$_SESSION['sessLogin_type'] = $_POST['login_type'];
		if ($_SESSION['sessLogin_type'] == "retailer") {
			//if selected type is retailer than check for valid retailer.
			$query_selectRetailer = "SELECT retailer_id,username,password FROM retailer WHERE username='$username' AND password='$password'";
			$result = mysqli_query($con, $query_selectRetailer);
			$row = mysqli_fetch_array($result);
			if ($row) {
				$_SESSION['retailer_id'] =  $row['retailer_id'];
				$_SESSION['sessUsername'] = $_POST['txtUsername'];
				$_SESSION['sessPassword'] = $_POST['txtPassword'];
				$_SESSION['retailer_login'] = true;
				header('Location:retailer/index.php');
			} else {
				$loginErr = "* Username or Password is incorrect.";
			}
		} else if ($_SESSION['sessLogin_type'] == "manufacturer") {
			//if selected type is manufacturer than check for valid manufacturer.
			$query_selectManufacturer = "SELECT man_id,username,password FROM manufacturer WHERE username='$username' AND password='$password'";
			$result = mysqli_query($con, $query_selectManufacturer);
			$row = mysqli_fetch_array($result);
			if ($row) {
				$_SESSION['manufacturer_id'] =  $row['man_id'];
				$_SESSION['sessUsername'] = $_POST['txtUsername'];
				$_SESSION['sessPassword'] = $_POST['txtPassword'];
				$_SESSION['manufacturer_login'] = true;
				header('Location:manufacturer/index.php');
			} else {
				$loginErr = "* Username or Password is incorrect.";
			}
		} else if ($_SESSION['sessLogin_type'] == "admin") {
			$query_selectAdmin = "SELECT username,password FROM admin WHERE username='$username' AND password='$password'";
			$result = mysqli_query($con, $query_selectAdmin);
			$row = mysqli_fetch_array($result);
			if ($row) {
				$_SESSION['admin_login'] = true;
				$_SESSION['sessUsername'] = $_POST['txtUsername'];
				$_SESSION['sessPassword'] = $_POST['txtPassword'];
				header('Location:admin/index.php');
			} else {
				$loginErr = "* Username or Password is incorrect.";
			}
		}
	} else {
		$reqErr = "* All fields are required.";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title> Login </title>
	<link rel="stylesheet" href="includes/main_style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<style>
		/* Container styling */
		.containers {
			display: flex;
			height: 100vh;
		}

		/* Left background image section */
		.background-image {
			flex: 1;
			background: url('./images/bg.jpg') no-repeat center center;
			background-size: cover;
		}

		/* Right login form section */
		.login-box {
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			background-color: #f4f4f4;
			padding: 20px;
		}

		/* Login form styling */
		.login-form {
			width: 100%;
			max-width: 600px;
			padding: 20px;
			margin-top: 10px;
		}

		.label-block {
			display: flex;
			flex-direction: column;
			gap: 5px;
		}

		input {
			width: 100%;
		}

		.form-list li {
			margin-bottom: 15px;
		}

		.submit_button {
			width: 100%;
			padding: 10px;
			background-color: #4CAF50;
			border: none;
			color: white;
			cursor: pointer;
		}

		.submit_button:hover {
			background-color: #45a049;
		}

		.error_message {
			color: red;
			font-size: 14px;
		}
	</style>
</head>

<div class="containers">
	<!-- Background Image Section -->
	<div class="background-image"></div>

	<!-- Login Form Section -->
	<div class="login-box">
		<h1>Welcome Back! 👋</h1>
		<form action="" method="POST" class="login-form">
			<ul class="form-list">
				<li>
					<div class="label-block"> <label for="login:username">Username</label> </div>
					<div class="input-box"> <input type="text" id="login:username" name="txtUsername" placeholder="Username" class="form-control mt-2" /> </div>
				</li>
				<li>
					<div class="label-block"> <label for="login:password">Password</label> </div>
					<div class="input-box"> <input type="password" id="login:password" name="txtPassword" class="form-control mt-2" placeholder="Password" /> </div>
				</li>
				<li>
					<div class="label-block"> <label for="login:type">Login Type</label> </div>
					<div class="input-box">
						<select name="login_type" class="form-control mt-2" id="login:type">
							<option value="" disabled selected>-- Select Type --</option>
							<option value="retailer">Retailer</option>
							<option value="manufacturer">Manufacturer</option>
							<option value="admin">Admin</option>
						</select>
					</div>
				</li>
				<li>
					<input type="submit" value="Login" class="btn btn-success" /> <span class="error_message"> <?php echo $loginErr;
																												echo $reqErr; ?> </span>
				</li>
			</ul>
		</form>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>