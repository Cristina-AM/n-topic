<?php include_once ('../config.php'); ?>
	<link rel="stylesheet" href="/ntopic/themes/default.theme.css">
	<title>nTopic - Main Page</title>
	</head>
	<?php include_once ('header.php');?>

		<main>
			<div class="container-fluid">
				<div class="row">
					<img class="img-responsive hidden-xs col-md-8 " src="/ntopic/resources/images/blue.img.jpg" alt="Social media image"> 
					<div class="col-md-1"></div>
					<div class="col-xs-6 col-md-3 topics"><?php
						// Displaying the topics
						$topic = DB::getInstance()->displayTopics();?>				
					</div>
				</div>
			</div>
		</main>
	</body>
</html>

	