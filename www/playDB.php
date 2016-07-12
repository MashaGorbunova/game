<?php
/**
* Connection with DataBase, different helper functions
* for creating and selection data from base. 
* @author Masha G (m.gorbunova@ukr.net)
*/
class PlayerDB {

/**
* establishment database connection
* @ return 
*/	
private function unitDB () {
	//your name of host
	$DB_host = 'localhost';
	
	//your name of user of DB
	$DB_user = 'root';
	
	//your password for DB
	$DB_password = '';
	
	//your name of DB
	$DB_name = 'play';
	
    error_reporting(E_ALL ^ E_NOTICE);
	
    $link = mysqli_connect($DB_host, $DB_user, $DB_password, $DB_name) or
        die("Could not connect: " . mysqli_error());
		
	return $link;
}

/**
* receiving request from the db
* @return request from the db
*/
function my_query ($sql_query) {
	$link = $this->unitDB();
	$res = mysqli_query($link, $sql_query) or die (mysqli_error($link));
	return $res;

}

/**
* creating new players and new game with this players.
* Incoming parametrs:
* $pl1 is name of player one
* $pl2 is name of player two
* $chip1 is mark of player one
* $chip2 is mark of player two
*/
function creatingPlayers ($pl1, $pl2, $chip1, $chip2) {

if (isset($pl1)&&!empty($pl1) && isset($pl2)&&!empty($pl2) &&
    isset($chip1)&&!empty($chip1) && isset($chip2)&&!empty($chip2)) {
		$p1 = trim (htmlspecialchars ($pl1, ENT_QUOTES));
        $p2 = trim (htmlspecialchars ($pl2, ENT_QUOTES));
        $chip1 = trim (htmlspecialchars ($chip1, ENT_QUOTES));
        $chip2 = trim (htmlspecialchars ($chip2, ENT_QUOTES));
        $date = date('Y-m-d', time());
		
		$query_player1 = "SELECT * FROM playerName WHERE player_name LIKE '$p1'";
		$player1 = $this->my_query($query_player1);
		
		if (mysqli_num_rows($player1)==0) {
			$sql1 = "INSERT INTO playerName (player_name) VALUES ('$p1')";
	        $this->my_query($sql1);
		}
		
		$query_player2 = "SELECT * FROM playerName WHERE player_name LIKE '$p2'";
		$player2 = $this->my_query($query_player2);
		
		if (mysqli_num_rows($player2)==0) {
			$sql2 = "INSERT INTO playerName (player_name) VALUES ('$p2')";
	        $this->my_query($sql2);
		}
		
		$row1 = mysqli_fetch_assoc($this->my_query($query_player1));
		$id_p1 = $row1['id'];
	
		$row2 = mysqli_fetch_assoc($this->my_query($query_player2));
		$id_p2 = $row2['id'];
		
		
		if ($chip1 != $chip2) {
			$query_chip1 = "SELECT id FROM chip WHERE chip LIKE '$chip1'";
		    $chip1 = $this->my_query($query_chip1);
	        $row1 = mysqli_fetch_assoc ($chip1);
            $id_chip1=$row1['id'];
			
			$query_chip2 = "SELECT id FROM chip WHERE chip LIKE '$chip2'";
		    $chip2 = $this->my_query($query_chip2);
            $row2 = mysqli_fetch_assoc ($chip2);
            $id_chip2=$row2['id'];
			
			$sql = "INSERT INTO game (player1, chip1, player2, chip2, date) VALUES ('$id_p1', '$id_chip1', '$id_p2', '$id_chip2', '$date')";
	        $this->my_query($sql);
	    }
		else {
	        if ($chip1=="crosses") {
		        $chip2="noughts";
	        }
	        else $chip2="crosses";
			
		    $query_chip1 = "SELECT id FROM chip WHERE chip LIKE '$chip1'";
		    $chip1 = $this->my_query($query_chip1);
	        $row1 = mysqli_fetch_assoc ($chip1);
            $id_chip1=$row1['id'];
			
			$query_chip2 = "SELECT id FROM chip WHERE chip LIKE '$chip2'";
		    $chip2 = $this->my_query($query_chip2);
            $row2 = mysqli_fetch_assoc ($chip2);
            $id_chip2=$row2['id'];
			
			$sql = "INSERT INTO game (player1, chip1, player2, chip2, date) VALUES ('$id_p1', '$id_chip1', '$id_p2', '$id_chip2', '$date')";
	        $this->my_query($sql);
        }	
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

	$sql1="SELECT id FROM playerName WHERE player_name = '$pl1'";
	$res1=$this->my_query($sql1);
	$row1=mysqli_fetch_assoc($res1);
	$name_win1=$row1['id'];
		
	$sql2="SELECT id FROM playerName WHERE player_name = '$pl2'";
	$res2=$this->my_query($sql2);
	$row2=mysqli_fetch_assoc($res2);
	$name_win2=$row2['id'];
		
	$sql3="SELECT id FROM chip WHERE chip = '$chip'";
	$res3=$this->my_query($sql3);
	$row3=mysqli_fetch_assoc($res3);
	$chip_win=$row3['id'];
		
	$sql4="SELECT id FROM game WHERE (player1 = '$name_win1' AND player2 = '$name_win2') ORDER BY id DESC LIMIT 1";
	$res4=$this->my_query($sql4);
	$row4=mysqli_fetch_assoc($res4);
	$id_game=$row4['id'];
		
	if ($pl == $pl1) {
		$sql="INSERT INTO wins (id_game, name_win, chip_win) VALUES ($id_game, $name_win1, $chip_win)";
		$this->my_query($sql);
	}
	if ($pl == $pl2) {
		$sql="INSERT INTO wins (id_game, name_win, chip_win) VALUES ($id_game, $name_win2, $chip_win)";
		$this->my_query($sql);
	}
	
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

    $date = date('Y-m-d', time());
		
	$row = mysqli_fetch_assoc ($this->my_query("SELECT id FROM playerName WHERE player_name LIKE '$pl1'"));		
    $id_p1=$row['id'];
	
    $row = mysqli_fetch_assoc ($this->my_query("SELECT id FROM playerName WHERE player_name LIKE '$pl2'"));
    $id_p2=$row['id'];

	$row = mysqli_fetch_assoc ($this->my_query("SELECT id FROM chip WHERE chip LIKE '$chip1'"));
    $id_chip1=$row['id'];
	
    $row = mysqli_fetch_assoc ($this->my_query("SELECT id FROM chip WHERE chip LIKE '$chip2'"));
    $id_chip2=$row['id'];
	
    $sql = "INSERT INTO game (player1, chip1, player2, chip2, date) VALUES ('$id_p1', '$id_chip1', '$id_p2', '$id_chip2', '$date')";
	$this->my_query($sql);
	
}
	
/**
* creating array with values from selecting from DB 
* for building table of 10 last winners of the game.
* Outcoming parametr is array of results from DB.
*/
function tableWins () {

	$res = $this->my_query("SELECT * FROM wins w INNER JOIN chip c ON c.id=w.chip_win INNER JOIN playerName p ON p.id=w.name_win ORDER BY id_game DESC LIMIT 10");
	$array = array ();
	$count = 0;
	while ($row = mysqli_fetch_assoc ($res)) {
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
