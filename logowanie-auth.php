<?php
	Session_start();
	$link = mysqli_connect("localhost", "root", "", "testowa");
?>	
	
<!DOCTYPE html>
<html lang="pl-PL">
	<head>
		<meta name="keyword" content="Strony na GITHub" />
		<meta name="description" content="Strony na GITHub" />
		<title>Strony i projekty na GITHuba</title>
	</head>
	<body>
		<h3>System logowania - Potwierdzenie zalogowania</h3>
		
		<div style="width: 900px; border: 1px solid #cccccc; margin: 5px; padding: 5px;">
			<?php

				//Nie pokazuje denerwujących błędów i bugów
				error_reporting(E_ALL & ~E_NOTICE);
				
				//Panel dla zalogowanych użytkowników serwisu
				if(isset($_POST['Zaloguj']))
				{
					$login = $_POST['login'];
					$password = $_POST['password'];
					
					//Przeciw atakom typu SQL Injection
					$login = stripslashes($login);
					$password = stripslashes($password);
					$login = mysqli_real_escape_string($link,$login);
					$password = mysqli_real_escape_string($link,$password);
					
					//Wybór z bazy danych do logowania
					$logowanie = "SELECT * FROM logowanie WHERE login='$login' and password='$password' LIMIT 1";
					$result = mysqli_query($link,$logowanie);
					if($result)
					{
						$row = mysqli_fetch_assoc($result);
						if($row)
						{
							//Dane przekazywane w sesji do logowania:: id - użytkownik - hasło - rola
							$_SESSION['zalogowany'] = 'true';
							$_SESSION['login'] = $row['login'];
							$_SESSION['password'] = $row['password'];
							$_SESSION['role'] = $row['role'];
							$_SESSION['id'] = $row['id'];
						}
						
						if($_SESSION['zalogowany']=='true')
						{
							echo 'Udało się pomyślnie zalogować<br />';
							
							//Przekierowanie na stronę główną
							header('Refresh: 3; URL=logowanie-baza.php');
							echo 'Za 3 sekundy zostaniesz przekierowany na stronę główną serwisu...';
						}	
						
						//Błąd logowania
						else
						{
							echo '<p>Nie udało się zalogować. Wróć do <a href="logowanie.php">STRONY GŁÓWNEJ</a> aby się zalogować.</p>';
						}
					}
					
					//Błąd logowania
					else
					{
						echo '<p>Błąd logowania poziom.</p>';
					}
				}
			?>
		</div>
	</body>
</html>