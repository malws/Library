<?php session_start();
      require_once('db.php');
	  
	  $id = $_GET['id'];
?>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<title>Biblioteka</title>
</head>

<body>
<header>
<h1><a href="index.php">Biblioteka</a></h1>
<p>Witaj, 
<?php  
echo $_SESSION['user'].'! ';
if ($_SESSION['auth'] == false)
{
	echo '<a href="login.php">Zaloguj się </a>';
}	
else
{
	echo '<a href="login.php?logout">Wyloguj się </a>';
	echo '<p><a href="myaccount.php"><span style="font-weight: bold"> Moje konto </span></a></p>';
}
?>
</p>
</header>
<?php
$table = explode(" ", $id);

if($id == 'new'):
?>
<h3 class="menu">Podaj dane książki</h3>
<form class="forms" method="post" action="">
<p><label for="tytul">Tytuł: </label><input type="text" name="tytul"></p>
<p><label for="autor">Autor: </label><input type="text" name="autor"></p>
<p><label for="wydawnictwo">Wydawnictwo: </label><input type="text" name="wydawnictwo"></p>
<p><label for="wydanie">Numer wydania: </label><input type="text" name="wydanie"></p>
<p><label for="strony">Liczba stron: </label><input type="text" name="strony"></p>
<p><label for="gatunek">Gatunek: </label><input type="text" name="gatunek"></p>

<input type="submit" name="addbook" value="Dodaj">
</form>
<?php
endif;

if(isset($_POST['addbook'])){
	$result=mysql_query("SELECT id_ksiazka FROM `ksiazka` WHERE `autor` = '".$_POST["autor"]."' AND `tytul` = '".$_POST["tytul"]."'")or die("Zapytanie niepoprawne");
	$sql = mysql_num_rows ($result);
	if($sql == 0) {		
		mysql_query("insert into ksiazka (autor, tytul, wydanie_ksiazki, strony_ksiazki, wydawnictwo, gatunek) values ('".$_POST["autor"]."','".$_POST["tytul"]."','".$_POST["wydanie"]."','".$_POST["strony"]."','".$_POST["wydawnictwo"]."','".$_POST["gatunek"]."')" )or die("Zapytanie niepoprawne");
		mysql_query("insert into egzemplarz (id_ksiazka, status) values ('".mysql_insert_id()."','0')")or die("Zapytanie niepoprawne");
		echo '<p style="padding-top:10px;color:red";>Dodano nowy zasób</p>';
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}	
	else {
		$row = mysql_fetch_assoc($result);
		mysql_query("insert into egzemplarz (id_ksiazka, status) values ('".$row['id_ksiazka']."','0')")or die("Zapytanie niepoprawne");
		echo '<p style="padding-top:10px;color:red";>Dodano nowy zasób</p>';
	}
	
}

if($table[0] == 'delete'){
	$result=mysql_query("SELECT * FROM `ksiazka` WHERE `id_ksiazka` = '".$table[1]."'")or die("Zapytanie niepoprawne");
	$result_egz=mysql_query("SELECT * FROM `egzemplarz` WHERE `id_ksiazka` = '".$table[1]."'")or die("Zapytanie niepoprawne");
	//$sql = mysql_num_rows ($result_egz);
	$row = mysql_fetch_assoc($result);
	//$row_egz = mysql_fetch_assoc($result);
	echo "<h3 class='menu'>Dostępne egzemplarze:</h3>";
	echo "<table>";
	echo "<tr><th>Nr egzemplarza</th><th>Tytuł</th><th>Autor</th></tr>";
				
	
	while ( $row_egz = mysql_fetch_assoc($result_egz) )
		{
			
				echo "<tr>";
				echo "<td>".$row_egz['id_egzemplarz']."</td>";
				echo "<td>".$row['tytul']."</td>";
				echo "<td>".$row['autor']."</td>";
				echo "<td><a href=\"books.php?id=egzemplarz ".$row_egz['id_egzemplarz']." ".$row['id_ksiazka']."\">Usuń</a></td>";
				//foreach ( $row as $col ) echo "<td>$col</td>";
				echo "</tr>"; 			
		}
	echo "</table>";

	
}

if($table[0] == 'egzemplarz') {
	$query=mysql_query("DELETE FROM egzemplarz WHERE id_egzemplarz='".$table[1]."'")or die("Zapytanie niepoprawne");
	$result_del=mysql_query("SELECT * FROM `egzemplarz` WHERE `id_ksiazka` = '".$table[2]."'")or die("Zapytanie niepoprawne");
	$sql_del = mysql_num_rows ($result_del);
	if($sql_del == 0){
		mysql_query("DELETE FROM ksiazka WHERE id_ksiazka='".$table[2]."'")or die("Zapytanie niepoprawne");
	}
	echo '<p style="padding-top:10px;color:red";>Zasób został usunięty.</p>';
	echo '<meta http-equiv="refresh" content="2; URL=index.php">';
}

if ($table[0] == 'reserve'){
	echo '<h3 class="menu">Zarezerwuj</h3>';
	$query_res = mysql_query("SELECT * FROM egzemplarz WHERE id_ksiazka='".$table[1]."' AND status='0'")or die("Zapytanie niepoprawne");
	$num_res = mysql_num_rows ($query_res);
	
		if(($_SESSION['auth'] == true) && ($_SESSION['permissions'] == 0)) {
			if($num_res == 0) {
				echo '<p style="padding-top:10px;color:red";>Brak dostępnych egzemplarzy. Proszę spróbować później!</p>';
			}
			else {
				$data = date_create();
				$datakoniec = date_create();
				date_add($datakoniec,date_interval_create_from_date_string("40 days"));
				$clientId = explode(" ", $_SESSION['user_id']);
				$row_res = mysql_fetch_assoc($query_res);
				mysql_query( "UPDATE egzemplarz SET status='1' WHERE id_egzemplarz=".$row_res['id_egzemplarz'] )or die("Zapytanie niepoprawne");
				mysql_query( "INSERT INTO rezerwacja (id_egzemplarz, id_czytelnik, data_poczatek, data_koniec) values ('".$row_res['id_egzemplarz']."','".$clientId[1]."','".date_format($data,"Y-m-d")."','".date_format($datakoniec,"Y-m-d")."')")or die("Zapytanie niepoprawne");
				echo '<p style="padding-top:10px;color:red";>Zarezerwowano egzemplarz.</p>';
			}
		}
		else if(($_SESSION['auth'] == false) && ($_SESSION['permissions'] == 0)) {
			echo '<p style="padding-top:10px;color:red";>Proszę się zalogować w celu dokonania rezerwacji.</p>';
			echo '<a href="login.php">Zaloguj się</a>';
		}
		else if(($_SESSION['auth'] == true) && ($_SESSION['permissions'] != 0)) {
			if($num_res == 0) {
				echo '<p style="padding-top:10px;color:red";>Brak dostępnych egzemplarzy. Proszę spróbować później!</p>';
			}
			else{
?>
		<form class="forms" action="" method="post">
          Numer czytelnika: <input type="text" name="numer" required/><br />
          <input type="submit" name="rezerwuj" value="Rezerwuj" />
      </form>	
<?php
		
		
		if(isset($_POST['rezerwuj'])) {
			$clientid = $_POST['numer'];
			$resultId = mysql_query("SELECT id_czytelnik FROM `czytelnik` WHERE `login_czytelnik` = '$clientid' ")or die("Niepoprawny identyfikator czytelnika");
			$rowId = mysql_fetch_assoc($resultId);
			$data = date_create();
			$datakoniec = date_create();
			date_add($datakoniec,date_interval_create_from_date_string("40 days"));
			$row_res = mysql_fetch_assoc($query_res);
			mysql_query( "UPDATE egzemplarz SET status='1' WHERE id_egzemplarz=".$row_res['id_egzemplarz'] )or die("Zapytanie niepoprawne");
			mysql_query( "INSERT INTO rezerwacja (id_egzemplarz, id_czytelnik, data_poczatek, data_koniec) values ('".$row_res['id_egzemplarz']."','".$rowId['id_czytelnik']."','".date_format($data,"Y-m-d")."','".date_format($datakoniec,"Y-m-d")."')")or die("Zapytanie niepoprawne");
			echo '<p style="padding-top:10px;color:red";>Zarezerwowano egzemplarz.</p>';
		}
			}
}
}
?>
<p><a href = "index.php">Wróć</a></p>
</body>
</html>