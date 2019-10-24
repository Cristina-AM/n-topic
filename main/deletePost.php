<?php
	include_once '../config.php';
?>
	<link rel="stylesheet" href="../themes/default.theme.css">
	</head>
	<?php include_once 'header.php';?>

		<main>
			<div class="container mainIndex" >
				<?php	
				
				$delete = DB::getInstance()->deletePost();

				?>
				
				
			</div>

		</main>
	</body>
</html>

	