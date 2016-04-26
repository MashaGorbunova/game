<?php
/**
* Connection with DataBase, different helper functions
* for creating and selection data from base. 
* @author Masha G (m.gorbunova@ukr.net)
*/
class PlayerDB {

/**
* establishment database connection
*/	
private function unitDB () {
	//your name of host
	$DB_host = 'localhost';
	
	//your name of user of DB
	$DB_user = 'root';
	
	//your password for DB
	$DB_password = '';
	
    error_reporting(E_ALL ^ E_NOTICE);
    mysql_connect ($DB_host, $DB_user, $DB_password) or
        die("Could not connect: " . mysql_error());
    mysql_select_db ('play');
}

/**
* creating new players and new game with this players.
* Imcoming parametrs:
* $pl1 is name of player one
* $pl2 is name of player two
* $chip1 is mark of player one
* $chip2 is mark of player two
*/
function creatingPlayers ($pl1, $pl2, $chip1, $chip2) {
$this->unitDB();

if (isset($pl1)&&!empty($pl1) && isset($pl2)&&!empty($pl2) &&
    isset($chip1)&&!empty($chip1) && isset($chip2)&&!empty($chip2)) {
		$p1 = trim (htmlspecialchars ($pl1, ENT_QUOTES));
        $p2 = trim (htmlspecialchars ($pl2, ENT_QUOTES));
        $chip1 = trim (htmlspecialchars ($chip1, ENT_QUOTES));
        $chip2 = trim (htmlspecialchars ($chip2, ENT_QUOTES));
        $date = date('Y-m-d', time());
		
		$respl1=mysql_query ("SELECT name_player FROM playerName WHERE name_player LIKE '$p1'") or die (mysql_error);
		if (mysql_num_rows($respl1)==0) {
			$sql1 = "INSERT INTO playerName (name_player) VALUES ('$p1')";
	        mysql_query ($sql1) or die (mysql_error ());
		}
		$respl2=mysql_query ("SELECT name_player FROM playerName WHERE name_player LIKE '$p2'") or die (mysql_error);
		if (mysql_num_rows($respl2)==0) {
			$sql2 = "INSERT INTO playerName (name_player) VALUES ('$p2')";
	        mysql_query ($sql2) or die (mysql_error ());
		}
		$row = mysql_fetch_assoc (mysql_query("SELECT id_player FROM playerName WHERE name_player LIKE '$p1'"));		
        $id_p1=$row['id_player'];
        $row = mysql_fetch_assoc (mysql_query("SELECT id_player FROM playerName WHERE name_player LIKE '$p2'"));
        $id_p2=$row['id_player'];

        if ($chip1 != $chip2) {
	        $row = mysql_fetch_assoc (mysql_query("SELECT id FROM chip WHERE name LIKE '$chip1'"));
            $id_chip1=$row['id'];
            $row = mysql_fetch_assoc (mysql_query("SELECT id FROM chip WHERE name LIKE '$chip2'"));
            $id_chip2=$row['id'];
			$sql = "INSERT INTO game (id_player1, id_chip1, id_player2, id_chip2, date) VALUES ('$id_p1', '$id_chip1', '$id_p2', '$id_chip2', '$date')";
	        mysql_query ($sql) or die (mysql_error ());
	    }
		else {
	        if ($chip1=="crosses") {
		        $chip2="noughts";
	        }
	        else $chip2="crosses";
		    $row = mysql_fetch_assoc (mysql_query("SELECT id FROM chip WHERE name LIKE '$chip1'"));
            $id_chip1=$row['id'];
            $row = mysql_fetch_assoc (mysql_query("SELECT id FROM chip WHERE name LIKE '$chip2'"));
            $id_chip2=$row['id'];

	        $sql = "INSERT INTO game (id_player1, id_chip1, id_player2, id_chip2, date) VALUES ('$id_p1', '$id_chip1', '$id_p2', '$id_chip2', '$date')";
	        mysql_query ($sql) or die (mysql_error ());
        }
        mysql_close();	
    }	
}


/**
* creating winners after the end of the game.
* Imcoming parametrs:
* $pl1 is name of player one
* $pl2 is name of player two
* $pl is name of player who won
* $chip is mark of player who won
*/	
function creatingWinners ($pl1, $pl2, $pl, $chip) {
	$this->unitDB();
		
	$sql1="SELECT id_player FROM playerName WHERE name_player = '$pl1'";
	$res1=mysql_query($sql1) or die (mysql_error());
	$row1=mysql_fetch_assoc($res1);
	$name_win1=$row1['id_player'];
		
	$sql2="SELECT id_player FROM playerName WHERE name_player = '$pl2'";
	$res2=mysql_query($sql2) or die (mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$name_win2=$row2['id_player'];
		
	$sql3="SELECT id FROM chip WHERE name = '$chip'";
	$res3=mysql_query($sql3) or die (mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$chip_win=$row3['id'];
		
	$sql4="SELECT id_game FROM game WHERE (id_player1 = '$name_win1' AND id_player2 = '$name_win2') ORDER BY id_game DESC LIMIT 1";
	$res4=mysql_query ($sql4) or die (mysql_error ());
	$row4=mysql_fetch_assoc($res4);
	$id_game=$row4['id_game'];
		
	if ($pl == $pl1) {
		$sql="INSERT INTO wins (name_win, chip_win, game) VALUES ($name_win1, $chip_win, $id_game)";
		mysql_query($sql) or die (mysql_error());
	}
	if ($pl == $pl2) {
		$sql="INSERT INTO wins (name_win, chip_win, game) VALUES ($name_win2, $chip_win, $id_game)";
		mysql_query($sql) or die (mysql_error());
	}
		
	mysql_close();
}

/**
* creating new game between this players again.
* Imcoming parametrs:
* $pl1 is name of player one
* $pl2 is name of player two
* $chip1 is mark of player one
* $chip2 is mark of player two
*/	
function creatingNewGame ($pl1, $pl2, $chip1, $chip2) {
    $this->unitDB();

    $date = date('Y-m-d', time());
		
	$row = mysql_fetch_assoc (mysql_query("SELECT id_player FROM playerName WHERE name_player LIKE '$pl1'"));		
    $id_p1=$row['id_player'];
	
    $row = mysql_fetch_assoc (mysql_query("SELECT id_player FROM playerName WHERE name_player LIKE '$pl2'"));
    $id_p2=$row['id_player'];

	$row = mysql_fetch_assoc (mysql_query("SELECT id FROM chip WHERE name LIKE '$chip1'"));
    $id_chip1=$row['id'];
	
    $row = mysql_fetch_assoc (mysql_query("SELECT id FROM chip WHERE name LIKE '$chip2'"));
    $id_chip2=$row['id'];
	
    $sql = "INSERT INTO game (id_player1, id_chip1, id_player2, id_chip2, date) VALUES ('$id_p1', '$id_chip1', '$id_p2', '$id_chip2', '$date')";
	mysql_query ($sql) or die (mysql_error ());
}
	
/**
* creating array with values from selecting from DB 
* for building table of 10 last winners of the game.
* Outcoming parametr is array of results from DB.
*/
function tableWins () {
	$this->unitDB();
	$res = mysql_query("SELECT * FROM wins INNER JOIN chip ON id=chip_win INNER JOIN playerName ON id_player=name_win ORDER BY game DESC LIMIT 10");
	$array = array ();
	$count = 0;
	while ($row = mysql_fetch_assoc ($res)) {
				$array [$count++] = $row;
	}
	return $array;
}

/**
* checking condition at the end of the game.
* Incoming parametr is array.
* If condition is true, function return true. 
*/
function filterFlag ($args = array()) {
	if (is_array($args)){
		$flag = TRUE;
	    if ((($args[1]=='X'&&$args[2]=='X'&&$args[3]=='X') or ($args[1]=='O'&&$args[2]=='O'&&$args[3]=='O')) || 
            (($args[1]=='X'&&$args[4]=='X'&&$args[7]=='X') or ($args[1]=='O'&&$args[4]=='O'&&$args[7]=='O')) || 
	        (($args[1]=='X'&&$args[5]=='X'&&$args[9]=='X') or ($args[1]=='O'&&$args[5]=='O'&&$args[9]=='O')) ||
            (($args[4]=='X'&&$args[5]=='X'&&$args[6]=='X') or ($args[4]=='O'&&$args[5]=='O'&&$args[6]=='O')) ||	 
	        (($args[7]=='X'&&$args[8]=='X'&&$args[9]=='X') or ($args[7]=='O'&&$args[8]=='O'&&$args[9]=='O')) || 
	        (($args[3]=='X'&&$args[6]=='X'&&$args[9]=='X') or ($args[3]=='O'&&$args[6]=='O'&&$args[9]=='O')) || 
	        (($args[3]=='X'&&$args[5]=='X'&&$args[7]=='X') or ($args[3]=='O'&&$args[5]=='O'&&$args[7]=='O')) ||
	        (($args[2]=='X'&&$args[5]=='X'&&$args[8]=='X') or ($args[2]=='O'&&$args[5]=='O'&&$args[8]=='O'))) 
		{
		        return $flag;
	    }	
	}	
}	
	
}
?>
