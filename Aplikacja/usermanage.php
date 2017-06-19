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

if(($id != 'new')&&($id != 'newczytelnik')&&($table[0] != 'edit')){
	$que=mysql_query("DELETE FROM ". $table[0] ." WHERE id_".$table[0]."=".$table[1])or die("Zapytanie niepoprawne");
	echo "Użytkownik został usunięty.";
	echo '<meta http-equiv="refresh" content="2; URL=index.php">';
}
	
/*
?>
	<h3 class="menu">Modyfikuj dane użytkownika</h3>
	<form method="post" action="">
	<p><label for="Ekonto">Numer konta: </label><input type="text" name="Ekonto"></p>
	<p><label for="Ehaslo">Hasło: </label><input type="text" name="Ehaslo"></p>
	<p><label for="Eimie">Imię: </label><input type="text" name="Eimie"></p>
	<p><label for="Enazwisko">Nazwisko: </label><input type="text" name="Enazwisko"></p>
	<p><label for="Eulica">Ulica: </label><input type="text" name="Eulica"></p>
	<p><label for="Ekod">Kod: </label><input type="text" name="Ekod"></p>
	<p><label for="Emiasto">Miasto: </label><input type="text" name="Emiasto"></p>
	<p><label for="Euprawnienia">Uprawnienia: </label><select name="Euprawnienia">
	<option value="1">Użytkownik</option>
	<option value="2">Administrator</option>
	</select></p>

	<input type="submit" name="editt" value="Modyfikuj">
	</form>
<?php
	endif;
	if(isset($_POST['editt'])){
		if(!empty($_POST['Ekonto'])) 
		{
			echo ($_POST['Ekonto']);
			$updates[] = "login_".$table[0]."='".$_POST['Ekonto']."'";
		}
		//echo $updates;
		//$sql=mysql_query( "UPDATE ".$table[0]." SET ".implode( ",", $updates )." WHERE id_".$table[0]."=".$table[1] )or die("Zapytanie niepoprawne");
	//echo "Zmodyfikowano dane użytkownika";
	}
}
print_r ($updates);
*/

if(($id == 'new') || (($table[0] == 'edit') && $table[1] == 'bibliotekarz')):
?>
<h3 class="menu">Podaj dane użytkownika</h3>
<form class="forms" method="post" action="">
<p><label for="konto">Numer konta: </label><input type="text" name="konto"></p>
<p><label for="haslo">Hasło: </label><input type="text" name="haslo"></p>
<p><label for="imie">Imię: </label><input type="text" name="imie"></p>
<p><label for="nazwisko">Nazwisko: </label><input type="text" name="nazwisko"></p>
<p><label for="adres">Adres: </label><input type="text" name="adres"></p>
<p><label for="uprawnienia">Uprawnienia: </label><select name="uprawnienia">
<option value="1">Użytkownik</option>
<option value="2">Administrator</option>
</select></p>

<input type="submit" name="add" value="Dodaj">
</form>
<?php
endif;
if(isset($_POST['add'])){
	if ($id == 'new') {
		$sql=mysql_query( "insert into bibliotekarz (login_bibliotekarz, password, permission, imie, nazwisko, adres) values ('".$_POST["konto"]."','".md5($_POST["haslo"])."','".$_POST["uprawnienia"]."','".$_POST["imie"]."','".$_POST["nazwisko"]."','".$_POST["adres"]."')" )or die("Zapytanie niepoprawne");
		echo "Dodano nowego użytkownika";
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}
	else if ($table[0] == 'edit') {
		$updates = array();
		if(!empty($_POST['konto'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "login_bibliotekarz='".$_POST['konto']."'";
		}
		if(!empty($_POST['haslo'])) 
		{
			//echo ($_POST['haslo']);
			$updates[] = "password='".md5($_POST['haslo'])."'";
		}
		if(!empty($_POST['imie'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "imie='".$_POST['imie']."'";
		}
		if(!empty($_POST['nazwisko'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "nazwisko='".$_POST['nazwisko']."'";
		}
		if(!empty($_POST['adres'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "adres='".$_POST['adres']."'";
		}
		if(!empty($_POST['uprawnienia'])) 
		{
			//echo ($_POST['uprawnienia']);
			$updates[] = "permission='".$_POST['uprawnienia']."'";
		}
		//print_r($updates);
		
		$sql=mysql_query( "UPDATE bibliotekarz SET ".implode( ",", $updates )." WHERE id_bibliotekarz=".$table[2] )or die("Zapytanie niepoprawne");
		echo "Zmodyfikowano dane użytkownika";
		unset($updates);
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}
	else {
		echo "Wystąpił błąd";
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}
}

if(($id == 'newczytelnik') || (($table[0] == 'edit') && $table[1] == 'czytelnik')):
?>
<h3 class="menu">Podaj dane czytelnika</h3>
<form class="forms" method="post" action="">
<p><label for="Ckonto">Numer konta: </label><input type="text" name="Ckonto"></p>
<p><label for="Chaslo">Hasło: </label><input type="text" name="Chaslo"></p>
<p><label for="Cimie">Imię: </label><input type="text" name="Cimie"></p>
<p><label for="Cnazwisko">Nazwisko: </label><input type="text" name="Cnazwisko"></p>
<p><label for="Cadres">Adres: </label><input type="text" name="Cadres"></p>
</select></p>

<input type="submit" name="Cadd" value="Dodaj">
</form>
<?php
endif;
if(isset($_POST['Cadd'])){
	if ($id == 'newczytelnik') {
		$sql=mysql_query( "insert into czytelnik (login_czytelnik, password_czytelnik, imie, nazwisko, adres) values ('".$_POST["Ckonto"]."','".md5($_POST["Chaslo"])."','".$_POST["Cimie"]."','".$_POST["Cnazwisko"]."','".$_POST["Cadres"]."')" )or die("Zapytanie niepoprawne");
		echo "Dodano nowego użytkownika";
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}
	else if ($table[0] == 'edit') {
		$updates = array();
		if(!empty($_POST['Ckonto'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "login_czytelnik='".$_POST['Ckonto']."'";
		}
		if(!empty($_POST['Chaslo'])) 
		{
			//echo ($_POST['haslo']);
			$updates[] = "password_czytelnik='".md5($_POST['Chaslo'])."'";
		}
		if(!empty($_POST['Cimie'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "imie='".$_POST['Cimie']."'";
		}
		if(!empty($_POST['Cnazwisko'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "nazwisko='".$_POST['Cnazwisko']."'";
		}
		if(!empty($_POST['Cadres'])) 
		{
			//echo ($_POST['konto']);
			$updates[] = "adres='".$_POST['Cadres']."'";
		}
		//print_r($updates);
		
		$sql=mysql_query( "UPDATE czytelnik SET ".implode( ",", $updates )." WHERE id_czytelnik=".$table[2] )or die("Zapytanie niepoprawne");
		echo "Zmodyfikowano dane czytelnika";
		unset($updates);
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}
	else {
		echo "Wystąpił błąd";
		echo '<meta http-equiv="refresh" content="2; URL=index.php">';
	}
	
}
?>
<p><a href = "index.php">Wróć</a></p>
</body>
</html>