<!--
LICENCE  - Chart
http://www.chartjs.org/"

Copyright (c) 2013-2015 Nick Downie
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
-->

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


	$query = pg_query($connection, "SELECT genre_id
		FROM movie_genre
		ORDER BY genre_id DESC");

	if (!$query) 
	{
	  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
	  //exit;
	}



	$size=0;
	$keys=[];
	$count=[];
	while ($row = pg_fetch_row($query))
	{
		if ($size == 0 || $keys[$size-1] != $row[0])
		{
			$keys[$size] 	= $row[0];
			$count[$size]	= 0;
			$size++;
		}
		$count[$size-1]++;
	}

	$genres=[];
	for ($i=0; $i<$size; $i++)
	{
		$query = pg_query($connection, "
			SELECT name
			FROM genre
			WHERE id = $keys[$i]
		");
		if (!$query) 
		{
		  echo '<div class="alert alert-danger" role="alert">Error with database!</div>';
		  exit;
		}
		$genres[$i] = pg_fetch_row($query)[0];
	}
	pg_close($connection);

	
	
//Build Webpage
	include './src/_colorHSL2RGB.php';
	for ($i=0; $i<$size; $i++)
	{
		$h 					= $i/$size;
		$color[$i] 			= ColorHSL2RGB($h, 0.9, 0.5);
		$highlightColor[$i]	= ColorHSL2RGB($h, 1.0, 0.7);
	}

	$title 	= "Filmy - genres";
	$active = 2;
	$header = "Gatunki filmów";
	$headerDesc = "Na diagramie kołowym przedstawiono popularność poszczególnych gatunków filmowych.";

	include  'templates/_head.php';
?>


<!-- BODY -->
<div class="container">
	<div class="row">
		
		<div class="col-md-8">
			<h2 class="sub-header">Diagram</h2>
			<p>Wykres wygenerowany przez <a href="http://www.chartjs.org/">ChartJS</a></p>
			<div id="canvas-holder">
				<canvas id="chart-area" width="500" height="500"/>
			</div>
		</div>
		
		<div class="col-md-4">
			<h2 class="sub-header">Legenda</h2>
				<?php
					for ($i=0; $i<$size; $i++)
					{
						echo "<h3><div class=\"label label-default \" style=\"background-color:$color[$i]; margin:30px; text-shadow: 0px 0px 3px #182019 \">
									$genres[$i]:  $count[$i]
								</div></h3>";
					}
				?>
		</div>
		
	</div>
</div>
		




<script>
	var pieData = 
	[
		<?php
			for ($i=0; $i<$size; $i++)
			{
				echo "{
					value: 		 $count[$i],
					color:		'$color[$i]',
					highlight: 	'$highlightColor[$i]',
					label: 		'$genres[$i]'
					}";
					
				if ($i < $size-1) echo ", ";
			}
		?>
	];
	
	window.onload = function()
	{
		var ctx = document.getElementById("chart-area").getContext("2d");
		var pie = new Chart(ctx).Pie(pieData, {responsive : true});
	};
</script>
				
		
<?php
	include  'templates/_foot.php';
	/*
	echo "<table>";
	echo 	"<tr>";
	echo 		"<td>Lp.</td>";
	echo 		"<td>Gatunek</td>";
	echo 		"<td>Wystąpienia</td>";
	echo 		"<td>ID gatunku</td>";
	echo 	"</tr>";

	for ($i=0; $i<$size; $i++)
	{
		$key = $keys[$i];
		$genre = $genres[$i];
		echo 	"<tr>";
		echo 		"<td>$i</td>";
		echo 		"<td>$genre[0]</td>";
		echo 		"<td>$count[$i]</td>";
		echo 		"<td>$key[0]</td>";
		echo 	"</tr>";
	}

	echo "</table>";
	*/
?>
