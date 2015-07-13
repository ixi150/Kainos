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

//Get all genres & IDs	
	$query = pg_query($connection, "
		SELECT id, name
		FROM genre
	");

	if (!$query) 
	{
	  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
	  exit;
	}

	$size			= 0;
	$genre		= [];
	$genreID	= [];
	$genreHash	= [];
	while ($row = pg_fetch_row($query))
	{
		$genre[$size] 						= $row[1];
		$genreID[$size]					= $row[0];			
		$genreHash	[$genreID[$size]] = $genre[$size];
		$size++;	
	}

//Search movies
	$selectedID 	= (isset($_POST["genre"])) 	? $_POST["genre"] 	: null;
	$selectedVote	= (isset($_POST["vote"])) 	? $_POST["vote"] 	: null;
	
	if ($selectedID > null)
	{
		$query = pg_query($connection, "
			SELECT title, vote_average, id FROM movie
			WHERE id IN 
			(
				SELECT movie_id FROM movie_genre WHERE genre_id = $selectedID
			)
			AND vote_average >= $selectedVote
			ORDER BY vote_average DESC
		");

		if (!$query) 
		{
		  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
		  exit;
		}
	}
	pg_close($connection);
	
	

	
//Build Webpage
	$title 			= "Filmy - search";
	$active 			= 3;
	$header 		= "Wyszukiwarka filmów";
	$headerDesc 	= "Tutaj znajdziesz listę filmów z twojego ulubionego gatunku o ocenie, którą sobie wyznaczysz. Znajdź film na długi zimowy wieczór!";
	include  'templates/_head.php';
?>




<!-- BODY -->
<div class="container">
	<div class="row">
		
		<div class="col-md-6">
			<h2 class="sub-header">Wyszukiwarka</h2>
			<p>Wybierz gatunek filmu jaki cię interesuje oraz jak bardzo wybredny jesteś w ocenie jakości filmu, a następnie kliknij przycisk Szukaj i ciesz się listą filmów na dziś!</p>
			<form class="form-horizontal" action="./search.php" method="post">
								
				<div class="form-group">
					<label class="col-sm-2 control-label">Gatunek filmu</label>
					<div class="col-sm-10">
						<select class="form-control" name="genre" required>
							<?php
								for ($i=0; $i<$size; $i++)
								{
										echo "<option value=\"$genreID[$i]\" ";
										if ($genreID[$i] == $selectedID) echo "selected";
										echo ">$genre[$i]</option>";
								}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Ocena minimalna</label>
					<div class="col-sm-10">
						<select class="form-control" name="vote" required>
							<?php
								for ($i=0.0; $i<=10.0; $i+=0.5)
								{
										echo "<option value=\"$i\"";
										if ($i == $selectedVote) echo "selected";
										echo ">$i</option>";
								}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-info btn-lg">Szukaj</button>
					</div>
				</div>
				
			</form>
		</div>
		
		
		<?php
		if ($selectedID > null)
		{
			echo "<div class=\"col-md-6\">";
			echo "<h2 class=\"sub-header\">Wyniki</h2>";
			echo "<table class=\"table table-bordered table-striped table-hover\">
						<thead>
						<tr>
							<th class='text-center'>Lp.</th>
							<th>Tytuł</th>
							<th class='text-center'>Ocena</th>
							<th class='text-center'>Opis</th>
						</tr>
						</thead>
						<tbody>";
			$i = 1;
			while ($row = pg_fetch_row($query))
			{
				echo 	"<tr>";
				echo 		"<td class='text-center'>" . $i++ . "</td>";
				echo 		"<td>$row[0]</td>";
				echo 		"<td class='text-center'>$row[1]</td>";
				echo 		"<td class='text-center'>
									<a class = \" btn-info btn \" href  = \"./movie.php?id=$row[2]\"> 
										Opis 
									</a>
								</td>";
				echo 	"</tr>";
			}
			echo "	</tbody>
					</table>";
			echo "</div>";
		}
			
		?>
		
	</div>
</div>
		

		
<?php
	include  'templates/_foot.php';
?>
