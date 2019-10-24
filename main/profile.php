<?php
	include_once '../config.php';
?>
	<link rel="stylesheet" href="../themes/default.theme.css">
	<title>nTopic - User profile</title>
	</head>
	<?php include_once 'header.php';?>


		<main>
		&nbsp;
			<div class="container">
				<div class="profile row">
					<?php	
						$db=DB::getInstance();
						$profile=$db->displayProfile();
						$deletePosts=$db->deleteUserPosts();
						$deleteAccount=$db->deleteAccount();
					?>
				</div>
			</div>
			
		</main>
	</body>
</html>

	