<?php
	include_once '../config.php';
?>
	<link rel="stylesheet" href="../themes/default.theme.css">
	<title>nTopic - Create new post</title>
	</head>
	<?php include_once 'header.php';
		if(isset($_GET['cat'])){
			$cat = $_GET['cat'];
			$createPost= DB::getInstance()->createPost($cat);
		}
	?>
		<main>
			<div class="container">
				<div class="row">
					<div class=" col-10">
						<form action="" id="publishForm" name="publishForm" method="POST">
							<input required type="text" name="postTitle" placeholder="Your title" id="title"/>
							<textarea required name="postContent" cols="40" rows="20"  placeholder="your post"></textarea>
							<input type="submit" name="publish" id="publish" value="Publish post">
						</form>
					</div>
				</div>
				<div class="col-2 sideTopics">
					<?php $topics= DB::getInstance()->displayTopics();?>
				</div>
			</div>
			
		</main>
	</body>
</html>

	