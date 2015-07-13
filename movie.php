<?php 
//Connect to database
	$connection = pg_connect
	("
		host='ec2-54-217-202-108.eu-west-1.compute.amazonaws.com' 
		port='5432' 
		dbname='d1s3rlsnijcgr9' 
		user='aisvdrmczgwwth' 
		password='mI_MJHGjcKy6_nILc7pPDRol5Y'
		sslmode='require'
	") 
	or die("Unable to connect database!");
	
	$id = (isset($_GET["id"])) 	? $_GET["id"] 	: null;
	if ($id != null)
	{
		$query = pg_query($connection, "
			SELECT title, vote_average, release_date
			FROM movie
			WHERE id = $id
		");
		if (!$query) 
		{
		  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
		  exit;
		}
		
		$row				= pg_fetch_row($query);
		$movie			= $row[0];
		$movieCode	= urlencode($movie);
		$vote			= $row[1];
		$year				= $row[2];
		$year 			= substr($year, 0, 4);
		$url 				= "http://www.omdbapi.com/?t=$movieCode&y=$year&plot=full";
		$json				= file_get_contents($url);
		$details			= json_decode($json);
		
		
		$query = pg_query($connection, "
			SELECT name
			FROM genre
			WHERE id IN
			(
				SELECT genre_id
				FROM movie_genre
				WHERE movie_id = $id
			)
		");
		if (!$query) 
		{
		  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
		  exit;
		}
		
		$size = 0;
		$genres = [];
		while ($row = pg_fetch_row($query))
		{
			$genres[$size++] = $row[0];
		}
	}

	pg_close($connection);
	
	
//Build Webpage
	$title 			= "Filmy - ID";
	$active 			= 4;
	$header 		= "Opis filmu";
	$headerDesc 	= "Przeczytaj szczegółowe informacje na temat wybranego przez siebie filmu.";
	include  'templates/_head.php';
?>




<!-- BODY -->

<div class="container ">
	<div class="row">
		
		

<?php 
	if ($id != null)
	{
		if($details->Response=='True' && $details->Poster != null && $details->Poster != "N/A")
		{
			echo 	'<div class="col-md-4 ">';
			echo 	"<img class='img-thumbnail img-responsive' src='" . $details->Poster . "' alt='poster'>";
			echo 	'</div>';
		}
		
		echo 	'<div class="col-md-4">';
		echo 		'<h2 class="sub-header">' . $movie . '</h2>';
		
		echo 		'<h3><span class="label label-success" style="text-shadow: 0px 0px 3px #182019">';
		echo 			"Ocena: " . $vote;			
		echo 		'</span></h3>';
		
		if ($size > 0)
		{
			echo 		'<h3><div class="alert alert-success" role="alert">';
			//echo 		"Gatunek:";
			for ($i = 0; $i < $size; $i++)
				echo ' ' . $genres[$i] . (($i < $size-1)?',':'');
			
			echo 		'</div></h3>';
			
		}
		
		if($details->Response=='True' && $details->Plot != null && $details->Plot != "N/A")
		{
			echo 		'<p>' . $details->Plot . '<br></p>';
			echo 		'<div id="google_translate_element"></div>';
		}
		else
		{
			echo 		'<div class="alert alert-danger" role="alert">Nie znaleziono opisu</div>';
		}
		echo 	'</div>';
	}
	
?>
	  
	
		<div class="col-md-3">
			<h2 class="sub-header">Wyszukiwarka</h2>
			<p></p>
			<form class="form-horizontal" action="./movie.php" method="get">
								
				<div class="form-group">
					<label class="col-sm-2 control-label">ID filmu</label>
					<div class="col-sm-10">
						<input class="form-control" name="id" placeholder="np. 10191" required 
							<?php
								if ($id != null) echo "value='$id'";
							?>
						>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-info btn-lg">Znajdź</button>
					</div>
				</div>
				
			</form>
		</div>
		
	</div>
</div>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
	function googleTranslateElementInit() 
	{
		new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'pl', multilanguagePage: true}, 'google_translate_element');
	}
</script>
		
<?php
	include  'templates/_foot.php';
?>
