<?php
	include_once '../config.php'
?>
	<link rel="stylesheet" href="/ntopic/themes/default.theme.css">
	<title>nTopic - Forum posts</title>
	</head>
	<?php include_once 'header.php';?>

		<main>
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-6 col-md-10 "><?php
						if($postId=isset($_GET['post'])){
							// Rendering the post's content
							$post= DB::getInstance()->renderPost($postId);
						}?>
					</div>					
					<div class="col-xs-12 col-md-2"><?php 
						$topics= DB::getInstance()->displayTopics();?>
					</div>
				</div>	
			</div>
		</main>
	</body>
</html>

	