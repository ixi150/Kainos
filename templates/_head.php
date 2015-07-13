<!DOCTYPE html>
<html lang="pl">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title>
		<?php 
			echo $title;
		?>
	</title>
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="src/Chart.Core.js"></script>
	<script src="src/Chart.Doughnut.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  </head>
  
  <body>
	<br><br><br><br>
  
	<!-- NAVBAR -->
	<div class = "navbar navbar-inverse navbar-fixed-top">
		<div class="container">
		
			<!--<a href="." class="navbar-brand">Strona Główna</a>-->
			
			<button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
				<span class = "icon-bar"></span>
				<span class = "icon-bar"></span>
				<span class = "icon-bar"></span>
			</button>
			
			<div class = "collapse navbar-collapse navHeaderCollapse">
				<ul class = "nav navbar-nav navbar-right">
					<li 
						<?php 
							if ($active == 1) echo 'class = "active"';
						?>
					><a href=".">Hity Filmowe</a></li>
					
					<li
						<?php 
							if ($active == 2) echo 'class = "active"';
						?>
					><a href="./topGenre.php">Gatunki Filmowe</a></li>
					
					<li
						<?php 
							if ($active == 3) echo 'class = "active"';
						?>
					><a href="./search.php">Znajdź Film</a></li>
					<li
						<?php 
							if ($active == 4) echo 'class = "active"';
						?>
					><a href="./movie.php">Opis filmu</a></li>
				</ul>
			</div>
			
		</div>
	</div>
  
  
  
	<!-- HEADER -->
	<div class="container">
		<div class="jumbotron text-center">
			<h1>
				<?php 
					echo $header;
				?>
			</h1>
			<p>
				<?php 
					echo $headerDesc;
				?>
			</p>
		</div>
	</div>
	
	
	
	<!-- BODY 
		.
		.
		.
	-->