<?php
	include_once '../config.php';
?>
	<link rel="stylesheet" href="../themes/default.theme.css">
	<title>nTopic - Main Page</title>
	</head>
	<?php
		if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
			$user = $_SESSION['user'];
		} else {
			header('Location: ../index.php');
		}
	?>
	<body>
		<header class="header">
			<div class="brand"><a href="index.php"><div class="chatBubble"><p>n</p></div> Topic</a></div>
				<div class="logout">
					<p>Welcome back, <a href="profile.php"><?=$user; ?></a> ! </p>
					<a href="logout.php"> Log out</a>
				</div>
			</div>
		</header> 
		<main>
			<div class="container">
				<div class="row">
					<div class="col-10">
						<?php
						
							if($postId=isset($_GET['post'])){
								// Rendering the post's content
								$post= DB::getInstance()->renderPost($postId);
							}			
						?>
					</div>
					<div class="col-2 sideTopics">
						<?php $topics= DB::getInstance()->displayTopics();?>
					</div>
				</div>	
			</div>
		</main>
	</body>
</html>

	