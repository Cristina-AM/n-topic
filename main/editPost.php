<?php
	include_once '../config.php';
?>
	<link rel="stylesheet" href="../themes/default.theme.css">
	<title>nTopic - Edit post</title>
	</head>
	<?php include_once 'header.php';?>
		<main>
			<div class="container">
				<div class="row">
					<div class=" col-10">
						<div class="title">Edit post</div><?php 
							$edit= DB::getInstance()->editPost();?>
						</div>
					</div>
				</div>
					<div class="col-2 sideTopics">
						<?php $topics= DB::getInstance()->displayTopics();?>
					</div>
				</div>
			</div>
		</main>
	</body>
</html>

	