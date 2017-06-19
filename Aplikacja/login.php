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
<h1>Biblioteka</h1>
</header>
<?php
	  if (!isset($_POST['login']) && !isset($_POST['password']) && $_SESSION['auth'] == FALSE) {
  ?>
		<h2 class="menu">Zaloguj</h2>
      <form class="forms" action="login.php" method="post">
          Login: <input type="text" name="login" required/><br />
          Hasło: <input type="password" name="password" required/>
          <input type="submit" name="zaloguj" value="Zaloguj" />
      </form>
  
  <?php
  }
	elseif (isset($_POST['login']) && isset($_POST['password']) && $_SESSION['auth'] == FALSE) {
      
             
		if (!empty($_POST['login']) && !empty($_POST['password'])) {
          
		
		$login = mysql_real_escape_string($_POST['login']);
		$password = mysql_real_escape_string($_POST['password']);
        
        
        $password = md5($password);
		
        
		$result = mysql_query("SELECT * FROM `bibliotekarz` WHERE `login_bibliotekarz` = '$login' AND `password` = '$password'");
		$sql = mysql_num_rows ($result);
		$row = mysql_fetch_assoc($result);
			if ($sql == 1) {
                $_SESSION['user'] = $login;
				$_SESSION['auth'] = TRUE;
				$_SESSION['permissions'] = $row['permission'];
				$_SESSION['user_id'] = "bibliotekarz ".$row['id_bibliotekarz'];
				
				echo '<p style="padding-top:10px;color:red";>Wczytuję...<br />';
				echo '<meta http-equiv="refresh" content="1; URL=index.php">';
		}
		else if ($sql == 0) {
              
                $result = mysql_query("SELECT * FROM `czytelnik` WHERE `login_czytelnik` = '$login' AND `password_czytelnik` = '$password'");
		$sql = mysql_num_rows ($result);
		$row = mysql_fetch_assoc($result);
			if ($sql == 1) {
              
				$_SESSION['user'] = $login;
				$_SESSION['auth'] = TRUE;
				$_SESSION['permissions'] = 0;
				$_SESSION['user_id'] = "czytelnik ".$row['id_czytelnik'];
				
				echo '<p style="padding-top:10px;color:red";>Wczytuję...<br />';
				echo '<meta http-equiv="refresh" content="1; URL=index.php">';
		}
		else {
				echo '<p style="padding-top:10px;color:red";>Błąd podczas logowania do systemu<br />';
				echo '<a href="login.php">Wróć do formularza</a></p>';
			}
		}
		
	}
	}
	// wyloguj się
	elseif ($_SESSION['auth'] == TRUE && isset($_GET['logout'])) {
		$_SESSION['user'] = 'gość';
		$_SESSION['auth'] = FALSE;
		echo '<meta http-equiv="refresh" content="1; URL=index.php">';
		echo '<p style="padding-top:10px"><strong>Proszę czekać...</strong><br />trwa wylogowywanie</p>';
		session_unset(); 
		session_destroy(); 
	}
  ?>
</body>
</html>