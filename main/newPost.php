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

		if(isset($_POST['publish'])){
			$postTitle = $_POST['postTitle'];
			$postContent = $_POST['postContent'];
			$topicId=$_POST['topicId'];
			if(empty($postContent) || empty($postTitle)){
				echo "You can't submit an empty post or without a name";
			}else{
				$createPost = DB::getInstance()->createPost($topicId);

			}
		}
		if(isset($_POST['checkAdm'])){
			$admCheck = DB::getInstance()->checkAdmin();
		}
	?>
	<body>
		<header class="header">
			<div class="brand"><a href="index.php"><div class="chatBubble"><p>n</p></div> Topic</a></div>
			<div class="logout">
				<p>Welcome back, <a href="profile.php"><?=$user; ?></a> !</p>
				<form action="index.php" method="GET">
					<a href="logout.php"> Log out</a>
				</form>
			</div>
		</header> 
		<main>
			<div class="container">
				<div class="row">
					<div class=" col-10">

						<form action="newPost.php" id="publishForm" name="publishForm" method="POST">
							<input required type="text" name="postTitle" placeholder="Your title" id="title"/>
							<textarea required name="postContent" cols="40" rows="20"  placeholder="your post"></textarea>
							<select name="topicId">
								<option value="1" selected>General</option>
								<option value="2">Productivity</option>
								<option value="3">Organization</option>
								<option value="4">Bullet-journal</option>
								<option value="5">entertainment</option>
							</select>
							<input required type="submit" name="publish" id="publish" value="Publish post">
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

	