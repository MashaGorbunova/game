<?php
session_start();
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>game</title>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<link href="css/styles.css" rel="stylesheet">
</head>

<body>

<div class="container border-container">
<div class="row center">
<header>
<h1 class="text-center">Game</h1>
<h2 class="text-center">Crosses & noughts</h2>
</header>
</div>

<div class="row">
<div class="col-lg-2 right visible-lg">
<img src="../www/images/x.png" alt="" class="img-responsive">
</div>
<div class="col-lg-8">
<div class="row">
<div class="col-lg-6 left">
<h3 class="text-center">Table of winners</h3>

<br><br>
<table class="table table-condensed text-center">
<?php
require_once "D:/programme/openserver/version5.2.4/openserver/domains/localhost/game/www/playDB.php";
$pl = new PlayerDB ();
$row = $pl -> tableWins();
$count = 1;

echo "<tr class='success'><th>Game</th><th>Name of winner</th><th>Mark of winner</th></tr>";
for ($i=0; $i<10; $i++) {
	echo "<tr>";
    echo "<td>", $count++, "</td><td>", $row[$i]['player_name'], "</td><td>", $row[$i]['chip'], "</td>";  				
	echo "</tr>";
}
?>
</table> 
<br>
<a class='btn btn-warning' href='start-page.php' role='button'>Ok</a>
</div>
<div class="col-lg-6 left">
<h3 class="text-center">Rules</h3>
<section>
<ol>
<li><p class="text-justify">This is logical game between 2 players.</p></li>
<li><p class="text-justify">One player uses "crosses" and second player uses "noughts".</p></li>
<li><p class="text-justify">In any space of a 3x3 grid first player makes his mark. Then the second player makes his mark.</p></li>
<li><p class="text-justify">If three same marks is in horizontal, vertical or diagonal space of a 3x3 grid, will be the end of the game.</p></li>
</ol>
</section>
</div>
</div>
</div>
<div class="col-lg-2 right visible-lg">
<img src="../www/images/o.png" alt="" class="img-responsive">
</div>
</div>
<div class="row">
<footer>
<p class="text-center">This game is made by Masha G, april 2016</p>
</footer>
</div>
</div>

</body>
</html>