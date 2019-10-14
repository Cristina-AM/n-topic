<?php
	// Creates a global vriable set to dirname(__FILE__), which points to the current folder this file is in right now
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
				echo "Congratsulations, your account was successfully created. You can log in now. 😊";	
			}
		} else {
			// Otherwise, no account is created
			echo "Couldn't create account, try again later";
		}		
	}

	// Log in the user
	if(isset($_POST['login'])){
		$username = $_POST['logUsername'];
		$password= $_POST['logPassword'];
		$loginUser = DB::getInstance()->logUser($username,$password);
		$user= $_SESSION['user'];
		if($user){
			header('Location: main/index.php');
		} else { echo "No user with such name";}
	}

?>
		<title>nTopic - Index</title>
	</head>
	<body class="landing">
		<!-- The log in header -->

		<header class="header">
			<div class="brand">
				<a href="index.php"><p class="chatBubble">n</p>Topic</a>		
			</div>
			<div class="login">
					<form action="index.php" method="POST">
					<label for="logUsername">Username: <input type="text" name="logUsername"></label>
					<label for="logPassword">Password: <input type="password" name="logPassword"></label>
					<input type="submit" name="login" value="Log in">
				</form>
			</div>
		</header> 



		<!-- The main content on the page -->

		<div class="row">
			<div class="col-3 signup">
				<h2 style="text-align:center;">Join us</h2>
				<form action="index.php" method="POST">
					<label for="username"> Username<input type="text" name="username" required="required" /></label> 
					<label for="password"> Password<input type="password" name="password" required="required" /></label> 
					<label for="rpassword"> Repeat password<input type="password" name="rpassword" required="required" /></label> 
					<input type="submit" name="register" value="Sign up" />
				</form>
			</div>
		</div>

	</div>
	</body>
</html>