<?php session_start();
      require_once('db.php');
	  
	  if(empty($_SESSION['user']))
	{
		$_SESSION['user'] = 'goÅ›Ä‡';
		$_SESSION['permissions'] = 'czytelnik';
	}
	
	$que = mysql_query("SELECT * FROM wypozyczenie");
	$dat1 = date_create();
	$today=date_format($dat1,"Y-m-d");
	while ( $rows = mysql_fetch_assoc($que) )
	{
		if(($rows['status'] == 0)&&($today > $rows['data_zwrotu']))
		{
			$quer = mysql_query("SELECT * FROM kara WHERE id_wypozyczenie='".$rows['id_wypozyczenie']."'");
			if(mysql_num_rows($quer) == 0)
			{
				mysql_query("INSERT INTO kara (id_wypozyczenie, data_kary, kwota, status) values ('".$rows['id_wypozyczenie']."','".$today."','0','0')");
			}
			else if(mysql_num_rows($quer) != 0)
			{
				while ( $roww = mysql_fetch_assoc($quer) )
				{
					
					if($roww['status'] == 0)
					{					
						$start = strtotime($rows['data_zwrotu']);
						$koniec = strtotime($today);
						$dni = ($koniec-$start)/(60*60*24);
						$kwota = 0.1 * $dni;
						mysql_query("UPDATE kara SET kwota='".$kwota."' WHERE id_kara=".$roww['id_kara']);
					}
				}
			}
		}
	}
	if(isset($_POST['wyszukaj_uzytkownikow'])) {
		$_SESSION['tabChecked'] = 2;
	}
	else if(isset($_POST['wyszukaj_czytelnikow'])) {
		$_SESSION['tabChecked'] = 3;
	}
	else if(isset($_POST['wyszukaj_rezerwacje'])) {
		$_SESSION['tabChecked'] = 4;
	}
	else if(isset($_POST['wyszukaj_wyp'])) {
		$_SESSION['tabChecked'] = 5;
	}
	else {
		$_SESSION['tabChecked'] = 1;
	}
?>

<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<title>Biblioteka</title>
</head>

<body>
<header>
<h1>Biblioteka</h1>
<p>Witaj, 
<?php  
echo $_SESSION['user'].'! ';
if ($_SESSION['auth'] == false)
{
	echo '<a href="login.php">Zaloguj siÄ™ </a>';
}	
else
{
	echo '<a href="login.php?logout">Wyloguj siÄ™ </a>';
	echo '<p><a href="myaccount.php"><span style="font-weight: bold"> Moje konto </span></a></p>';
}
?>
</p>
</header>

<h2 class="menu">Wyszukaj w bibliotece</h2>
<ul class="tabs">
<li>
<input type="radio" name="tabs" id="tab1" <?php if ($_SESSION['tabChecked'] == 1) echo "checked"; ?> />
<label for="tab1" class="tablab">Zasoby</label>
<div id="tab-content1" class="tab-content">
<form id="form-wyszukiwanie" action="" method="post">
<p><input type="text" name="search" placeholder="Podaj tytuÅ‚ lub autora">
<input type="submit" name="wyszukaj" value="Wyszukaj" /></p>
</form>


<?php
if($_SESSION['permissions'] == 2):
?>
<a href="books.php?id=new">Dodaj nowy zasÃ³b</a>
<?php
endif;
if(isset($_POST['wyszukaj'])) {
	if(!empty($_POST["search"])) {
		$regex = '/.*'.$_POST["search"].'.*/';
		$sql=mysql_query( "select * from ksiazka" )or die("Zapytanie niepoprawne");
		$rescount = 0;
		echo "<div class='content'>";
		echo "<table>";
		echo "<tr><th>TytuÅ‚</th><th>Autor</th><th>Wydawnictwo</th><th>Numer wydania</th><th>Liczba stron</th><th>Gatunek</th></tr>";

		while ( $row = mysql_fetch_assoc($sql) )
		{
			if((preg_match($regex,$row['tytul'])) || (preg_match($regex,$row['autor'])))
			{
				echo "<tr>";
				echo "<td>".$row['tytul']."</td>";
				echo "<td>".$row['autor']."</td>";
				echo "<td>".$row['wydawnictwo']."</td>";
				echo "<td>".$row['wydanie_ksiazki']."</td>";
				echo "<td>".$row['strony_ksiazki']."</td>";
				echo "<td>".$row['gatunek']."</td>";
				echo "<td><a href=\"books.php?id=reserve ".$row['id_ksiazka']."\">Zarezerwuj egzemplarz</a></td>";
				if($_SESSION['permissions'] == 2) {
					echo "<td><a href=\"books.php?id=delete ".$row['id_ksiazka']."\">UsuÅ„</a></td>";
				}
				//foreach ( $row as $col ) echo "<td>$col</td>";
				echo "</tr>"; 		
				$rescount ++;
			}
		}
		echo "</table>";
		echo 'Znaleziono '.$rescount.' pasujÄ…cych wynikÃ³w.';
		echo "</div>";
	}
}
?>
</div>
</li>

<?php

if($_SESSION['permissions'] == 2):
?>

<li>
<input type="radio" name="tabs" id="tab2" <?php if ($_SESSION['tabChecked'] == 2) echo "checked"; ?>/>
<label for="tab2" class="tablab">Pracownicy</label>
<div id="tab-content2" class="tab-content">
<form id="form-uzytkownicy" action="" method="post">
<p><input type="text" name="search_users" placeholder="Podaj nazwisko">
<input type="submit" name="wyszukaj_uzytkownikow" value="Wyszukaj" /></p>
</form>
<a href="usermanage.php?id=new">Dodaj nowego uÅ¼ytkownika</a>

<?php
	endif;
	if(isset($_POST['wyszukaj_uzytkownikow'])) {
	if(!empty($_POST["search_users"])) {
		$regex = '/.*'.$_POST["search_users"].'.*/';
		$sql=mysql_query( "select * from bibliotekarz" )or die("Zapytanie niepoprawne");
		$rescount = 0;
		echo "<div class='content'>";
		echo "<table>";
		echo "<tr><th>Login</th><th>ImiÄ™</th><th>Nazwisko</th><th>Adres</th></tr>";

		while ( $row = mysql_fetch_assoc($sql) )
		{
			if((preg_match($regex,$row['login_bibliotekarz'])))
			{
				echo "<tr>";
				echo "<td>".$row['login_bibliotekarz']."</td>";
				echo "<td>".$row['imie']."</td>";
				echo "<td>".$row['nazwisko']."</td>";
				echo "<td>".$row['adres']."</td>";
				echo "<td><a href=\"usermanage.php?id=user ".$row['id_bibliotekarz']."\">Usuñ</a></td>";
				echo "<td><a href=\"usermanage.php?id=edit bibliotekarz ".$row['id_bibliotekarz']."\">Edytuj</a></td>";
				//foreach ( $row as $col ) echo "<td>$col</td>";
				echo "</tr>"; 		
				$rescount ++;
			}
		}
		echo "</table>";
		echo 'Znaleziono '.$rescount.' pasujÄ…cych wynikÃ³w.';
		echo "</div>";
	}
}
?>
</div>
</li>
<?php
if($_SESSION['permissions'] != 0){
?>

<li>
<input type="radio" name="tabs" id="tab3" <?php if ($_SESSION['tabChecked'] == 3) echo "checked"; ?>/>
<label for="tab3" class="tablab">Czytelnicy</label>
<div id="tab-content3" class="tab-content">
<form id="form-czytelnicy" action="" method="post">
<p><input type="text" name="search_clients" placeholder="Podaj nazwisko">
<input type="submit" name="wyszukaj_czytelnikow" value="Wyszukaj" /></p>
</form>
<a href="usermanage.php?id=newczytelnik">Dodaj nowego czytelnika</a>

<?php
	
	if(isset($_POST['wyszukaj_czytelnikow'])) {
	if(!empty($_POST["search_clients"])) {
		$regex = '/.*'.$_POST["search_clients"].'.*/';
		$sql=mysql_query( "select * from czytelnik" )or die("Zapytanie niepoprawne");
		$rescount = 0;
		echo "<div class='content'>";
		echo "<table>";
		echo "<tr><th>Numer</th><th>ImiÄ™</th><th>Nazwisko</th><th>Adres</th></tr>";
		while ( $row = mysql_fetch_assoc($sql) )
		{
			if((preg_match($regex,$row['login_czytelnik']))||(preg_match($regex,$row['nazwisko'])))
			{
				echo "<tr>";
				echo "<td>".$row['login_czytelnik']."</td>";
				echo "<td>".$row['imie']."</td>";
				echo "<td>".$row['nazwisko']."</td>";
				echo "<td>".$row['adres']."</td>";
				echo "<td><a href=\"usermanage.php?id=czytelnik ".$row['id_czytelnik']."\">Usuñ</a></td>";
				echo "<td><a href=\"usermanage.php?id=edit czytelnik ".$row['id_czytelnik']."\">Edytuj</a></td>";
				
				//foreach ( $row as $col ) echo "<td>$col</td>";
				echo "</tr>"; 		
				$rescount ++;
			}
		}
		echo "</table>";
		echo 'Znaleziono '.$rescount.' pasujÄ…cych wynikÃ³w.';
		echo "</div>";
	}
}

?>
</div>
</li>
<li>
<input type="radio" name="tabs" id="tab4" <?php if ($_SESSION['tabChecked'] == 4) echo "checked"; ?>/>
<label for="tab4" class="tablab">Rezerwacje</label>
<div id="tab-content4" class="tab-content">
<form id="form-rezerw" action="" method="post">
<p><input type="text" name="search_res" placeholder="Id czytelnika">
<input type="submit" name="wyszukaj_rezerwacje" value="Wyszukaj"/></p>
</form>
<?php
if(isset($_POST['wyszukaj_rezerwacje'])) {
	if(!empty($_POST["search_res"])) {
		$result = mysql_query("SELECT * FROM `czytelnik` WHERE `login_czytelnik` = '".$_POST["search_res"]."'");
		$row = mysql_fetch_assoc($result);
		$resultres = mysql_query("SELECT * FROM `rezerwacja` WHERE `id_czytelnik` = '".$row['id_czytelnik']."'");
		
		//$rowres = mysql_fetch_assoc($resultres);
		echo "<div class='content'>";
		echo "<p>Rezerwacje czytelnika ".$row['login_czytelnik']."</p>";
		echo "<table>";
		echo "<tr><th>Nr rezerwacji</th><th>Autor</th><th>TytuÅ‚</th><th>Nr egzemplarza</th></tr>";
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
		echo "</div>";
	}
}
?>
</div>
</li>
<li>
<input type="radio" name="tabs" id="tab5" <?php if ($_SESSION['tabChecked'] == 5) echo "checked"; ?>/>
<label for="tab5" class="tablab">WypoÅ¼yczenia</label>
<div id="tab-content5" class="tab-content">
<form id="form-wyp" action="" method="post">
<p><input type="text" name="search_borr" placeholder="Id czytelnika/TytuÅ‚ ksiÄ…Å¼ki">
<input type="submit" name="wyszukaj_wyp" value="Wyszukaj" /></p>
</form>
<a href="borrow.php?id=newborrow">Nowe wypoÅ¼yczenie</a>

<?php
	if(isset($_POST['wyszukaj_wyp'])) {
		if(!empty($_POST["search_borr"])) {
			$resultb = mysql_query("SELECT * FROM `czytelnik` WHERE `login_czytelnik` = '".$_POST["search_borr"]."'");
			$sqlb = mysql_num_rows ($resultb);
			if ($sqlb != 0)
			{
				$roww = mysql_fetch_assoc($resultb);
				$resbor = mysql_query("SELECT * FROM wypozyczenie WHERE id_czytelnik = '".$roww['id_czytelnik']."' AND status='0'");
				$numbor = mysql_num_rows ($resbor);
				if($numbor != 0)
				{
					echo "<div class='content'>";
					echo "<p>WypoÅ¼yczenia czytelnika ".$_POST["search_borr"]."</p>";
					echo "<table>";
					echo "<tr><th>Autor</th><th>TytuÅ‚</th><th>Numer egzemplarza</th><th>Nr wypoÅ¼yczenia</th></tr>";
					while($rowbor = mysql_fetch_assoc($resbor))
					{
						$resee = mysql_query("SELECT * FROM egzemplarz WHERE id_egzemplarz = '".$rowbor['id_egzemplarz']."'");
						$rowee = mysql_fetch_assoc($resee);
						$resb = mysql_query("SELECT * FROM ksiazka WHERE id_ksiazka = '".$rowee['id_ksiazka']."'");
						$rowb = mysql_fetch_assoc($resb);
						$reskara = mysql_query("SELECT * FROM kara WHERE id_wypozyczenie = '".$rowbor['id_wypozyczenie']."' AND status='0'");
						
						echo "<tr>";
						echo "<td>".$rowb['autor']."</td>";
						echo "<td>".$rowb['tytul']."</td>";
						echo "<td>".$rowee['id_egzemplarz']."</td>";
						echo "<td>".$rowbor['id_wypozyczenie']."</td>";
						echo "<td><a href=\"borrow.php?id=back ".$rowbor['id_wypozyczenie']."\">Zwrot</a></td>";
						if(mysql_num_rows($reskara)!=0)
						{
							$rowkara = mysql_fetch_assoc($reskara);
							echo "<td>KARA: ".$rowkara['kwota']." zÅ‚</td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					echo "</div>";
				}
			}
			else
			{
				$resb = mysql_query("SELECT * FROM ksiazka WHERE tytul = '".$_POST["search_borr"]."'");
				$rowb = mysql_fetch_assoc($resb);
				$rese = mysql_query("SELECT * FROM egzemplarz WHERE id_ksiazka = '".$rowb['id_ksiazka']."'");
				echo "<div class='content'>";
				echo "<table>";
				echo "<tr><th>Autor</th><th>TytuÅ‚</th><th>Numer egzemplarza</th><th>Nr wypoÅ¼yczenia</th><th>Czytelnik</th></tr>";
				while($rowe = mysql_fetch_assoc($rese))
				{
					$resbor = mysql_query("SELECT * FROM wypozyczenie WHERE id_egzemplarz = '".$rowe['id_egzemplarz']."' AND status='0'");
					$numbor = mysql_num_rows ($resbor);
					if($numbor != 0)
					{
						$rowbor = mysql_fetch_assoc($resbor);
						$resclient = mysql_query("SELECT * FROM czytelnik WHERE id_czytelnik = '".$rowbor['id_czytelnik']."'");
						$rowclient = mysql_fetch_assoc($resclient);
						echo "<tr>";
						echo "<td>".$rowb['autor']."</td>";
						echo "<td>".$rowb['tytul']."</td>";
						echo "<td>".$rowe['id_egzemplarz']."</td>";
						echo "<td>".$rowbor['id_wypozyczenie']."</td>";
						echo "<td>".$rowclient['login_czytelnik']."</td>";
						echo "<td><a href=\"borrow.php?id=back ".$rowbor['id_wypozyczenie']."\">Zwrot</a></td>";
						echo "</tr>"; 		
						
					}
				}
				echo "</table>";
				echo "</div>";
			}
		}
	}
}
?>
</div>
</li>
</ul>
</body>
</html>
