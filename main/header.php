<?php 
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
		$user = $_SESSION['user'];
		$admin=DB::getInstance()->checkAdmin();?>
		<header class="header">
			<nav class="navbar navbar-default">
				<div class="row">
					<div class="col">
						<div class="container-fluid" style="padding:0 !important;">
							<div class="navbar-header ">
								<a class="main-brand" href="/ntopic/main/index.php"><p class="chatBubble">n</p>Topic</a>		
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2 col-sm-2 col-md-12" >
						<div class="nav navbar-nav login navbar-right logout">
							<p style="text-align:center;">Welcome back, <a href="/ntopic/main/profile.php"><?=$user; ?></a> !</p>
							<a id="logout" href="/ntopic/main/logout.php"> Log out</a>
							<?php if($admin){ echo '<a class="panel" href="/ntopic/main/cpanel.php">Control Panel</a>'; } ?>
						</div>
					</div>
				</div>					
			</nav>
		</header><?php
	} else {?>
		<header>
			<nav class="navbar navbar-default">
				<div class="row">
					<div class="col">
						<div class="container-fluid" style="padding:0 !important;">
							<div class="navbar-header ">
								<a class="brand" href="index.php"><p class="chatBubble">n</p>Topic</a>		
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2 col-sm-2 col-md-12" >
						<div class="nav navbar-nav login navbar-right">
							<form action="index.php" method="POST">
								<label for="logUsername">Username:</label> 
								<input type="text" name="logUsername">
								<label for="logPassword">Password:</label> 
								<input type="password" name="logPassword">
								<input type="submit" id="loginBtn" name="login" value="Log in">
							</form>
						</div>
					</div>
				</div>					
			</nav>
		</header><?php 
		if(isset($_POST['login'])){
			$user=$_POST['logUsername'];
			$pass=$_POST['logPassword'];
			$logUser=DB::getInstance()->logUser($user,$pass);
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
	}
?>