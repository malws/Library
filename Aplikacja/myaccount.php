<?php session_start();
      require_once('db.php');
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
echo '<a href="login.php?logout">Wyloguj się</a>';
$table = explode(" ", $_SESSION['user_id']);
if ($table[0] == 'czytelnik'){
	$result = mysql_query("SELECT * FROM `czytelnik` WHERE `id_czytelnik` = '".$table[1]."'");
}
else {
	$result = mysql_query("SELECT * FROM `bibliotekarz` WHERE `id_bibliotekarz` = '".$table[1]."'");
}
$row = mysql_fetch_assoc($result);

?>
</header>
<h2 class="menu">Moje konto</h2>
<ul class="tabs">
<li>
<input type="radio" name="tabs" id="tab1" checked />
<label for="tab1" class="tablab">Zmień dane</label>
<div id="tab-content1" class="tab-content">
<form class="forms" action="" method="post">
<p><label for="konto">Numer konta: </label><input type="text" name="konto" placeholder="<?php if($table[0] == 'bibliotekarz') {echo $row['login_bibliotekarz'];} else {echo $row['login_czytelnik'];} ?>"></p>
<p><label for="haslo">Hasło: </label><input type="text" name="haslo" placeholder=""></p>
<p><label for="powhaslo">Powtórz hasło: </label><input type="text" name="powhaslo" placeholder=""></p>
<p><label for="imie">Imię: </label><input type="text" name="imie" placeholder="<?php echo $row['imie']; ?>"></p>
<p><label for="nazwisko">Nazwisko: </label><input type="text" name="nazwisko" placeholder="<?php echo $row['nazwisko']; ?>"></p>
<p><label for="ulica">Adres: </label><input type="text" name="adres" placeholder="<?php echo $row['adres']; ?>"></p>
<p><input type="submit" name="zmien" value="Zmień" /></p>
</form>
<?php
if(isset($_POST['zmien'])){
	$updates = array();
	$go = true;
	if($table[0] == 'bibliotekarz') {
		if(!empty($_POST['konto'])) 
		{
			$updates[] = "login_bibliotekarz='".$_POST['konto']."'";
		}
		if(!empty($_POST['haslo'])) 
		{
			if(!empty($_POST['powhaslo'])) 
			{
				if($_POST['haslo'] == $_POST['powhaslo'])
				{
					$updates[] = "password='".md5($_POST['haslo'])."'";
				}
				else
				{
					$go = false;
					echo "Podane hasła nie są identyczne";
				}
			}
			else 
			{
				$go = false;
				echo "Proszę podać dwa razy nowe hasło";
			}
		}
		if(!empty($_POST['imie'])) 
		{
			$updates[] = "imie='".$_POST['imie']."'";
		}
		if(!empty($_POST['nazwisko'])) 
		{
			$updates[] = "nazwisko='".$_POST['nazwisko']."'";
		}
		if(!empty($_POST['adres'])) 
		{
			$updates[] = "adres='".$_POST['adres']."'";
		}
		if($go == true){
			$sql=mysql_query( "UPDATE bibliotekarz SET ".implode( ",", $updates )." WHERE id_bibliotekarz=".$table[1] )or die("Zapytanie niepoprawne");
			echo "Zmodyfikowano dane użytkownika";
			unset($updates);
		}
	}
	else if($table[0] == 'czytelnik') {
		if(!empty($_POST['konto'])) 
		{
			$updates[] = "login_czytelnik='".$_POST['konto']."'";
		}
		if(!empty($_POST['haslo'])) 
		{
			if(!empty($_POST['powhaslo'])) 
			{
				if($_POST['haslo'] == $_POST['powhaslo'])
				{
					$updates[] = "password_czytelnik='".md5($_POST['haslo'])."'";
				}
				else
				{
					$go = false;
					echo "Podane hasła nie są identyczne";
				}
			}
			else 
			{
				$go = false;
				echo "Proszę podać dwa razy nowe hasło";
			}
		}
		if(!empty($_POST['imie'])) 
		{
			$updates[] = "imie='".$_POST['imie']."'";
		}
		if(!empty($_POST['nazwisko'])) 
		{
			$updates[] = "nazwisko='".$_POST['nazwisko']."'";
		}
		if(!empty($_POST['adres'])) 
		{
			$updates[] = "adres='".$_POST['adres']."'";
		}
		if($go == true){
			$sql=mysql_query( "UPDATE czytelnik SET ".implode( ",", $updates )." WHERE id_czytelnik=".$table[1] )or die("Zapytanie niepoprawne");
			echo "Zmodyfikowano dane użytkownika";
			unset($updates);
		}
	}
}
?>
</div>
</li>
<?php
if(($_SESSION['auth'] == true) && ($_SESSION['permissions'] == 0)){
?>

<li>
<input type="radio" name="tabs" id="tab2" />
<label for="tab2" class="tablab">Moje rezerwacje</label>
<div id="tab-content2" class="tab-content">
<?php
		$myId = explode(" ", $_SESSION['user_id']);
		$resultres = mysql_query("SELECT * FROM `rezerwacja` WHERE `id_czytelnik` = '".$myId[1]."'");
		$sql = mysql_num_rows ($resultres);
		if($sql != 0)
		{
			echo "<table>";
				echo "<tr><th>Nr rezerwacji</th><th>Autor</th><th>Tytuł</th><th>Nr egzemplarza</th></tr>";
				while ( $rowres = mysql_fetch_assoc($resultres) )
				{
					$resultegz = mysql_query("SELECT * FROM `egzemplarz` WHERE `id_egzemplarz` = '".$rowres['id_egzemplarz']."'");
					$rowegz = mysql_fetch_assoc($resultegz);
					$resultbook = mysql_query("SELECT * FROM `ksiazka` WHERE `id_ksiazka` = '".$rowegz['id_ksiazka']."'");
					$rowbook = mysql_fetch_assoc($resultbook);
			
					echo "<tr>";
					echo "<td>".$rowres['id_rezerwacja']."</td>";
					echo "<td>".$rowbook['autor']."</td>";
					echo "<td>".$rowbook['tytul']."</td>";
					echo "<td>".$rowegz['id_egzemplarz']."</td>";
					echo "<td><a href=\"borrow.php?id=reservation ".$rowres['id_rezerwacja']."\">Anuluj</a></td>";
						//foreach ( $row as $col ) echo "<td>$col</td>";
					echo "</tr>"; 			
			
				}
			echo "</table>";
		}
		else 
		{
			echo "Nie masz obecnie rezerwacji.";
		}
	
?>
</div>
</li>
<li>
<input type="radio" name="tabs" id="tab3" />
<label for="tab3" class="tablab">Moje wypożyczenia</label>
<div id="tab-content3" class="tab-content">

<?php
		$myId = explode(" ", $_SESSION['user_id']);
		$resultresw = mysql_query("SELECT * FROM `wypozyczenie` WHERE `id_czytelnik` = '".$myId[1]."'");
		$sqlw = mysql_num_rows ($resultresw);
		if($sqlw != 0)
		{
			echo "<table>";
				echo "<tr><th>Autor</th><th>Tytuł</th><th>Nr egzemplarza</th><th>Data wypożyczenia</th><th>Data zwrotu</th></tr>";
				while ( $rowresw = mysql_fetch_assoc($resultresw) )
				{
					$resultegzw = mysql_query("SELECT * FROM `egzemplarz` WHERE `id_egzemplarz` = '".$rowresw['id_egzemplarz']."'");
					$rowegzw = mysql_fetch_assoc($resultegzw);
					$resultbookw = mysql_query("SELECT * FROM `ksiazka` WHERE `id_ksiazka` = '".$rowegzw['id_ksiazka']."'");
					$rowbookw = mysql_fetch_assoc($resultbookw);
					$reskara = mysql_query("SELECT * FROM kara WHERE id_wypozyczenie = '".$rowresw['id_wypozyczenie']."' AND status='0'");
						
			
					echo "<tr>";
					echo "<td>".$rowbookw['autor']."</td>";
					echo "<td>".$rowbookw['tytul']."</td>";
					echo "<td>".$rowegzw['id_egzemplarz']."</td>";
					echo "<td>".$rowresw['data_wypozyczenia']."</td>";
					echo "<td>".$rowresw['data_zwrotu']."</td>";
					if($rowresw['status'] == '0')
					{
						echo "<td>NIE ZWRÓCONO</td>";
						if(mysql_num_rows($reskara)!=0)
						{
							$rowkara = mysql_fetch_assoc($reskara);
							echo '<td style="padding-top:10px;color:red";>KARA: '.$rowkara['kwota']." zł</td>";
						}
					}
					else
					{
						echo "<td>Zwrócono</td>";
					}
					echo "</tr>"; 			
			
				}
			echo "</table>";
		}
		else 
		{
			echo "Nie masz wypożyczeń.";
		}
}
?>
</div>
</li>
</ul>
</body>
</html>