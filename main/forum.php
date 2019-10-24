<?php
	include_once ('../config.php');
?>
	<link rel="stylesheet" href="/ntopic/themes/default.theme.css">
	<title>nTopic - Forum</title>
	</head>
	<?php include_once ('header.php');?>

		<main>
			<div class="container-fluid">
				<div class="row">
					<img class="img-responsive hidden-xs col-md-8 " src="/ntopic/resources/images/blue.img.jpg" alt="Social media image"> 
					<div class="col-md-1"></div>
					<div class="col-xs-6 col-md-3"><?php	
						// Displaying the topics
						if(isset($_GET['cat'])){
							$topicId=$_GET['cat'];
							echo "<a href='newPost.php?cat=".$topicId."' class='newPost'>Create a post</a>";
							$posts= DB::getInstance()->displayPosts($topicId);			
						}?>
					</div>
				</div>				
			</div>
		</main>
	</body>
</html>

	