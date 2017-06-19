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

if($table[0] == 'reservation')
{
	$resultegz = mysql_query("SELECT id_egzemplarz FROM rezerwacja WHERE `id_rezerwacja` = '".$table[1]."'");
	$rowegz = mysql_fetch_assoc($resultegz);
	mysql_query("DELETE FROM rezerwacja WHERE id_rezerwacja=".$table[1])or die("Zapytanie niepoprawne");
	mysql_query( "UPDATE egzemplarz SET status='0' WHERE id_egzemplarz=".$rowegz['id_egzemplarz'] )or die("Zapytanie niepoprawne");
	echo '<p style="padding-top:10px;color:red";>Rezerwacja została anulowana.</p>';
}

if($id == 'newborrow')
{
?>
	<h2 class="menu">Nowe wypożyczenie</h2>
	<form class="forms" action="" method="post">
          Numer czytelnika: <input type="text" name="czytel" required/><br />
		  Numer egzemplarza: <input type="text" name="egzem" required/><br />
          <input type="submit" name="wypoz" value="Dodaj" />
     </form>
<?php
	if(isset($_POST['wypoz'])) {
		$resultclient = mysql_query("SELECT id_czytelnik FROM czytelnik WHERE login_czytelnik = '".$_POST['czytel']."'");
		$rowclient = mysql_fetch_assoc($resultclient);
		$data = date_create();
		$datakoniec = date_create();
		date_add($datakoniec,date_interval_create_from_date_string("40 days"));
		$resultem = mysql_query("SELECT status FROM egzemplarz WHERE id_egzemplarz = '".$_POST['egzem']."'");
		$rowem = mysql_fetch_assoc($resultem);
		if(($rowem['status'] == '0')||(mysql_num_rows(mysql_query("SELECT id_rezerwacja FROM rezerwacja WHERE id_egzemplarz='".$_POST['egzem']."' AND id_czytelnik = '".$rowclient['id_czytelnik']."'")) != 0))
		{
				mysql_query( "INSERT INTO wypozyczenie (id_egzemplarz, id_czytelnik, data_wypozyczenia, data_zwrotu, status) values ('".$_POST['egzem']."','".$rowclient['id_czytelnik']."','".date_format($data,"Y-m-d")."','".date_format($datakoniec,"Y-m-d")."','0')")or die("Zapytanie niepoprawne");
			if(mysql_num_rows(mysql_query("SELECT id_rezerwacja FROM rezerwacja WHERE id_egzemplarz='".$_POST['egzem']."' AND id_czytelnik = '".$rowclient['id_czytelnik']."'")) != 0)
			{
				mysql_query("DELETE FROM rezerwacja WHERE id_egzemplarz='".$_POST['egzem']."' AND id_czytelnik = '".$rowclient['id_czytelnik']."'")or die("Zapytanie niepoprawne");
			}
			else
			{
				mysql_query( "UPDATE egzemplarz SET status='1' WHERE id_egzemplarz=".$_POST['egzem'] )or die("Zapytanie niepoprawne");
			}
			
			echo '<p style="padding-top:10px;color:red";>Dodano wypożyczenie.</p>';
		}
		else
		{
			echo '<p style="padding-top:10px;color:red";>Egzemplarz nie jest aktualnie wolny<p>';
		}
		
	}
}

if($table[0] == 'back')
{
	$resbor = mysql_query("SELECT * FROM wypozyczenie WHERE id_wypozyczenie = '".$table[1]."'");
	$rowbor = mysql_fetch_assoc($resbor);
	$data = date_create();
	mysql_query( "UPDATE wypozyczenie SET data_zwrotu='".date_format($data,"Y-m-d")."', status='1' WHERE id_wypozyczenie=".$table[1] )or die("Zapytanie niepoprawne");
	mysql_query( "UPDATE egzemplarz SET status='0' WHERE id_egzemplarz=".$rowbor['id_egzemplarz'] )or die("Zapytanie niepoprawne");
	$reskara = mysql_query("SELECT * FROM kara WHERE id_wypozyczenie = '".$table[1]."' AND status='0'");
	if(mysql_num_rows($reskara) != 0)
	{
		mysql_query( "UPDATE kara SET status='1' WHERE id_wypozyczenie=".$table[1] );
	}	
	
	echo '<p style="padding-top:10px;color:red";>Zwrot został odnotowany w bazie danych.</p>';
}
?>
<p><a href = "index.php">Wróć</a></p>
</body>
</html>