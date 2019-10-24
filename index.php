<?php
	session_start();
	session_destroy();
	// The configuration file is required in order for the page to load
	require_once 'config.php';

	// Checks if the register form was submitted
	if(isset($_POST['register'])){
		// Retrieves the data from the form and stores it in variables
		$username = $_POST['username'];
		$password= $_POST['password'];
		$rPassword=$_POST['rpassword'];
		// Checks if the passwords match
		if($password === $rPassword){
			// Checks if the username is available for registering
			$usernameAvailable = DB::getInstance()->checkUsername($username);
			// If it is, the newUser method from the DB class is called, which inserts a user in the database 
			if($usernameAvailable){
				$newUser= DB::getInstance()->newUser($username,$password);
				echo "<div class='row'>
							<div class='col-12 bg-success'>
								Congratsulations, your account was successfully created. You can log in now.
							</div>
						</div>";	
			}
		} else {
			// Otherwise, no account is created
			echo "<div class='row'>
						<div class='col-12 bg-danger'>Couldn't create account, try again later</div>
					</div>";
		}		
	}?>
		<title>nTopic - Index</title>
	</head>
	<body  style="background-color:#3bb1e0;">
		<?php require_once 'main/header.php'; ?>

		<!-- The main content on the page -->
		<div class="container-fluid">
			<div class="row">
				<img class="img-responsive hidden-xs col-md-8 " src="/ntopic/resources/images/blue.img.jpg" alt="Social media image"> 
				<div class="col-md-1"></div>
				<div class="col-xs-6 col-md-3 signup">
					<h2 style="text-align:center;">Join us</h2>
					<form action="index.php" method="POST">
						<div class="form-group">	
							<label for="username"> Username</label>
							<input type="text" class="form-control" name="username" required="required" /> 
						</div>
						<div class="form-group">
							<label for="password"> Password</label>
							<input type="password" class="form-control" name="password" required="required" />
						</div>
						<div class="form-group">
							<label for="rpassword"> Repeat password</label>
							<input type="password" class="form-control" name="rpassword" required="required" />
						</div>
						<input type="submit" id="register" name="register" value="Sign up" />

					</form>
				</div>
			</div>
		</div>
	</body>
</html>