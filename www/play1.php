<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT']."/game/www/playDB.php";
$p = new PlayerDB ();

$c=array();
for ($i=1;$i<10; $i++) {
$c[$i] = strtoupper(trim (htmlspecialchars ($_POST['c'.$i], ENT_QUOTES)));
}

$_SESSION['c']=$c;
$file = basename($_SERVER['PHP_SELF'], ".php");
$flag = $p->filterFlag($_SESSION['c']);
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
<h3 class="text-center">The first player move</h3>
<br>
<?php
if ($flag) 
{
	if ($file == "play1") {
		echo "<p class='text-success'>Winner is ".$_SESSION['player2'].".</p><br>";
	    $p->creatingWinners($_SESSION['player1'], $_SESSION['player2'], $_SESSION['player2'], $_SESSION['chip2']);
    }
    if ($file=="play2") {
	    echo "<p class='text-success'>Winner is ".$_SESSION['player1'].".</p><br>";
	    $p->creatingWinners($_SESSION['player1'], $_SESSION['player2'], $_SESSION['player1'], $_SESSION['chip1']);
    }
	echo "<p class='text-success'>Game over. New game?</p>"; 
	
	echo "<a class='btn btn-success' href='play1.php' role='button'>Yes"; 
	$p->creatingNewGame($_SESSION['player1'], $_SESSION['player2'], $_SESSION['chip1'], $_SESSION['chip2']);
	//unset ($_SESSION['c']);
    echo "</a> ";
    echo "<a class='btn btn-success' href='end-page.php' role='button'>No";
	echo"</a>";
}
else {
    if ($file == "play1") {
	    echo "<p class='info'>Player 1: ". $_SESSION['player1'].".</p>";
	    echo "<p class='info'> Mark is ". $_SESSION['chip1'].".</p>";
    }
    if ($file=="play2") {
	    echo "<p class='info'>Player 2: ". $_SESSION['player2'].".</p>";
	    echo "<p class='info'> Mark is ". $_SESSION['chip2']."</p>";
    }
}
?>
<br><br>

<div>
<form action="play2.php" method="post">
<div  class="border-table">
<table class="style-table">
<tr><td><input type="text" pattern="[X,O, x, o]" name="c1" value="<?php echo $_SESSION['c'][1];?>" <?php if (!empty($_SESSION['c'][1])) echo "readonly='readonly'"; ?>></td>
<td><input type="text" pattern="[X,O, x, o]" name="c2" value="<?php echo $_SESSION['c'][2];?>" <?php if (!empty($_SESSION['c'][2])) echo "readonly='readonly'"; ?>></td>
<td><input type="text" pattern="[X,O, x, o]" name="c3" value="<?php echo $_SESSION['c'][3];?>" <?php if (!empty($_SESSION['c'][3])) echo "readonly='readonly'"; ?>></td></tr>
<tr><td><input type="text" pattern="[X,O, x, o]"  name="c4" value="<?php echo $_SESSION['c'][4];?>" <?php if (!empty($_SESSION['c'][4])) echo "readonly='readonly'"; ?>></td>
<td><input type="text" pattern="[X,O, x, o]" name="c5" value="<?php echo $_SESSION['c'][5];?>" <?php if (!empty($_SESSION['c'][5])) echo "readonly='readonly'"; ?>></td>
<td><input type="text" pattern="[X,O, x, o]" name="c6" value="<?php echo $_SESSION['c'][6];?>" <?php if (!empty($_SESSION['c'][6])) echo "readonly='readonly'"; ?>></td></tr>
<tr><td><input type="text" pattern="[X,O, x, o]" name="c7" value="<?php echo $_SESSION['c'][7];?>" <?php if (!empty($_SESSION['c'][7])) echo "readonly='readonly'"; ?>></td>
<td><input type="text" pattern="[X,O, x, o]" name="c8" value="<?php echo $_SESSION['c'][8];?>" <?php if (!empty($_SESSION['c'][8])) echo "readonly='readonly'"; ?>></td>
<td><input type="text" pattern="[X,O, x, o]" name="c9" value="<?php echo $_SESSION['c'][9];?>" <?php if (!empty($_SESSION['c'][9])) echo "readonly='readonly'"; ?>></td></tr>
</table>
</div>

<br> <br>
<?php
if (!$flag) {
	echo "<button type='submit' class='btn btn-warning' title='X or O'>ok</button>";
} 
?>
</form>
</div>
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
