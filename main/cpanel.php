<?php
	include_once ('../config.php');
?>
	<link rel="stylesheet" href="/ntopic/themes/default.theme.css">
	<title>nTopic - Main Page</title>
	</head>
	<?php
		if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
			$user = $_SESSION['user'];
			// $displayProfile= DB::getInstance()->getProfileInfo($user);
		} else {
			header('Location: /ntopic/main/index.php');
		}
	?>
	<body>
		<?php include_once ('header.php');?>
		<main>
			<div class="container-fluid">
				<div class="row">
					<img class="img-responsive hidden-xs col-md-8 " src="/ntopic/resources/images/blue.img.jpg" alt="Social media image"> 
					<div class="col-xs-6 col-md-4"><?php
						$db =  DB::getInstance();
						// Displaying the topics
						$newTopic = $db->createTopic();		
						echo "<div class='title'> Topics</div>";
						$topics = $db->displayAllTopics();		
						$delete= $db->deleteTopic();
						// Displaying the posts in the cpanel
						echo "<div class='title'> Posts</div>";		
						$posts= $db->displayAllPosts();?>
					</div>				
				</div>							
			</div>

		</main>
	</body>
</html>

	