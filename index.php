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

	
	$query = pg_query($connection, "SELECT title, vote_average, id
		FROM movie
		ORDER BY vote_average DESC");
		
	pg_close($connection);

	if (!$query) {
	  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
	  exit;
	}

//Build Webpage
	$title 	= "Filmy - home";
	$active = 1;
	$header = "TOP 20 najlepszych filmów";
	$headerDesc = "Oto 20 najlepszych filmów wszechczasów!
					Jeśli nie zgadzasz się z tą lista napisz do mnie na FaceBook'u.";

	include  'templates/_head.php';
?>

<!-- BODY -->
<div class="container">
	<div class="row">
		<div class="col-md">
			<h2 class="sub-header">Tabela najlepszych filmów wszechczasów</h2>
			<table class="table table-bordered table-striped table-hover">
				<thead>
				<tr>
					<th class='text-center'>Lp.</th>
					<th>Tytuł</th>
					<th class='text-center'>Ocena</th>
					<th class='text-center'>Opis</th>
				</tr>
				</thead>
				<tbody>
<?php
	for ($i=1; $i<=20; $i++)
	{
		$row = pg_fetch_row($query);
		echo 	"<tr>";
		echo 		"<td class='text-center'>$i</td>";
		echo 		"<td>$row[0]</td>";
		echo 		"<td class='text-center'>$row[1]</td>";
		echo 		"<td class='text-center'>
						<a class = \" btn-info btn \" href  = \"./movie.php?id=$row[2]\"> 
							Opis 
						</a>
					 </td>";
		echo 	"</tr>";
	}
	
?>
			</tbody>
			</table>
		</div>
	</div>
</div>

<?php
	include  'templates/_foot.php';
?>














