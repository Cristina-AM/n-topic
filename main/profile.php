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
				<p>Welcome back, <a href="profile.php"><?=$user; ?></a> ! </p>
				<form action="index.php" method="POST">
					<a href="logout.php"> Log out</a>
				</form>
			</div>
		</header> 
		<main>
		&nbsp;
			<div class="container">
				<div class="profile row">
					<?php	
						$profile=DB::getInstance()->displayProfile();
					?>
				</div>
			</div>
			
		</main>
	</body>
</html>

	