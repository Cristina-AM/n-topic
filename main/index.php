<?php
	include_once '../config.php';
?>
	<link rel="stylesheet" href="../themes/default.theme.css">
	<title>nTopic - Main Page</title>
	</head>
	<?php
		if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
			$user = $_SESSION['user'];
			// $displayProfile= DB::getInstance()->getProfileInfo($user);
		} else {
			header('Location: ../index.php');
		}
	?>
	<body>
		<header class="header">
			<div class="brand"><a href="index.php"><div class="chatBubble"><p>n</p></div> Topic</a></div>
				<div class="logout">
					<p>Welcome back, <a href="profile.php"><?=$user; ?></a> !</p>
					<a href="logout.php"> Log out</a>
				</div>
			</div>
		</header> 
		<main>
			<div class="container mainIndex" >
				<img src="../resources/images/blue.img.jpg" alt="background image">				
				<?php	
					// Displaying the topics
					$topic = DB::getInstance()->displayTopics();
					
				?>

				
			</div>

		</main>
	</body>
</html>

	