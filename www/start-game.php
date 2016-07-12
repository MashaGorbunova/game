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
<h3 class="text-center">Start game</h3>
<br>
<canvas id='table' width="195" height="195">
    <p>This is a 3x3 grid.</p>
 </canvas>
<script>
var table = document.getElementById ("table");
var context = table.getContext('2d');
context.fillStyle="tomato";
context.fillRect(7, 7, 180, 180);
context.strokeStyle="grey";
context.lineWidth=3;
context.strokeRect(0, 0, 195, 195);
context.strokeRect (7, 7, 181, 181);
for (i=0; i<3; i++) {
	for (j=0; j<3; j++) {
		context.strokeRect (7+i*60, 7+j*60, 60, 60);
	}
}
</script>
<br><br>
<form action= "play1.php" method="post" role="form">
<?php
require_once $_SERVER['DOCUMENT_ROOT']."/game/www/playDB.php";
$pl = new PlayerDB ();
$pl -> creatingPlayers ($_POST [player1], $_POST [player2], $_POST [chip1], $_POST [chip2]);
$_SESSION['player1'] = $_POST [player1];
$_SESSION['player2'] = $_POST [player2];
$_SESSION['chip1'] = $_POST [chip1];
$_SESSION['chip2'] = $_POST [chip2];
if ($_SESSION['chip1'] == $_SESSION['chip2']) {
	if ($_SESSION['chip1'] == "crosses") {
		$_SESSION['chip2'] = "noughts";
	}
	if ($_SESSION['chip1'] == "noughts") {
		$_SESSION['chip2'] = "crosses";
	}
}
echo "<p class='text-success'> Player 1: ". $_SESSION['player1'].". -> Mark is: ". $_SESSION['chip1'].".</p>";
echo "<p class='text-success'> Player 2: ". $_SESSION['player2'].". -> Mark is: ". $_SESSION['chip2'].".</p>";
?>
 
<br>
<button type="submit" class="btn btn-warning">start!</button>
</form>

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
