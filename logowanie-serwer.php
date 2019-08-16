<?php
	Session_start();
	$link = mysqli_connect("localhost", "root", "", "testowa");
	
	//Przypomnienie zapomnianego hasła
	if(isset($_GET['przypomnij']))
	{
		$id = $_GET['przypomnij'];
		$result = mysqli_query($link, "SELECT * FROM logowanie WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		$login = $row['login'];
		$password = $row['password'];
		$email = $row['email'];
		
		//Wysyłanie maila z przypomnieniem hasła
		$tresc = "Nazwa: $login. Zapomniane hasło to: $password. Spróbuj się zalogować jeszcze raz.";
		$header =  "From: A L \nContent-Type:".
				   ' text/plain;charset="UTF-8"'.
				  "\nContent-Transfer-Encoding: 8bit";
				
		mail($email, 'Kontakt ze strony www.logowanie.pl', $tresc, $header);
		
		//Potwierdzenie wysłania maila
		echo 'Wiadomość odnośnie przypomnienia hasła została wysłana. <br />';
		
		//Przekierowanie na stronę główną
		header('Refresh: 5; URL=logowanie.php');
		echo 'Zaraz zostaniesz przekierowany na stronę główną seriwsu...';
	}
	
	//Dodawanie nowego użytkownika do bazy danych przez niezalogowanego użytkownika - informacja o dodaniu/nie dodaniu użytkownika
	if(isset($_POST['Zarejestruj']))
	{
		if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['email']))
		{
			$login = $_POST['login'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];
			$role = $_POST['role'];
			$data = $_POST['data'];
			$data = date('Y-m-d H:i:s');
			
			//
			//Sprawdzanie czy podana wartość użytkownika i emaila jest JUŻ w bazie danych
			/*
			$login = trim($_POST['login']);
			$login_z_bazy = "SELECT login FROM logowanie WHERE login='$login'";
			$rekord = mysqli_query($link,$login_z_bazy);
			
			$email = trim($_POST['email']);
			$email_z_bazy = "SELECT email FROM logowanie WHERE email='$email'";
			$rekord2 = mysqli_query($link,$email_z_bazy);
			if(mysqli_num_rows($rekord)==0)
			{
				echo 'Rekord zajęty - login';
			}
			else if(mysqli_num_rows($rekord2)==0)
			{
				echo 'Rekord zajęty - email';
			}
			*/
			//
			
			if(strlen($login) < 3 || strlen($login) > 25)
			{
				echo 'Nieprawidłowa długość w wartości <b>Login</b>. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-zarejestruj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else if(strlen($password) < 3 || strlen($password) > 25)
			{
				echo 'Nieprawidłowa długość w wartości <b>Hasło</b>. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-zarejestruj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else if($password2 != $password)
			{
				echo 'Pola wartości <b>Hasło</b> i <b>Powtórz hasło</b> są różne. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-zarejestruj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else if(preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]$/', $email))
			{
				echo 'Nieprawidłowa długość w wartości <b>Email</b>. Użyto niedozwolonych znaków. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-zarejestruj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else
			{
				$result = "INSERT INTO logowanie (login, password, email, role, data) VALUES('$login', '$password', '$email', 'role', '$data')";
				if(mysqli_query($link,$result))
				{
					echo 'Użytkownik został zarejestrowany w systemie <br /><br />';
					echo 'Zapisane dane: <br />
					  Podana nazwa <b>[Login]</b>: <b>'.$login.'</b><br />
					  Podane hasło <b>[Hasło]</b>: <b>'.$password.'</b><br />
					  Podany adres e-mail <b>[Email]</b>: <b>'.$email.'</b><br />
					  Dane zapisano z datą: <b>'.$data.'</b><br />';
				
					//Wysłanie maila do zarejestrowanego użytkownika
					//UWAGA - zmien treść
					$tresc = "Witam: $login.<br /> Zarejestrowałeś/aś się do naszego systemu.<br />Podałeś dane: login - $login, hasło - $password.";
					$header =  "From: A L \nContent-Type:".
								' text/plain;charset="UTF-8"'.
								"\nContent-Transfer-Encoding: 8bit";
				
					mail($email, 'Kontakt ze strony www.logowanie.pl', $tresc, $header);
		
					//Potwierdzenie wysłania maila
					echo 'Wiadomość odnośnie potwierdzenia rejestracji nowego użytkownika została wysłana do użytkownika mailem. <br />';
					
					//Przekierowanie na stronę główną
					header('Refresh: 5; URL=logowanie-zarejestruj.php');
					echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
				}
				else
				{
					echo "Błąd: " . $result . "<br>" . mysqli_error($link);
				}	
			}
		}
		else
		{
			echo 'Błąd rejestracji';
		}
	}
	
	//////// Funkcje dla zalogowanych/zarejestrowanych użytkowników systemu /////////
	if($_SESSION['zalogowany']=='true')
	{
		if(isset($_GET['pokaz']))
		{
			$id = $_GET['pokaz'];
			$result = mysqli_query($link,"SELECT * FROM logowanie WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			
			//Pokazanie wyświetlanych danych
			echo '<p> ID - '.$row['id'].' - Login - '.$row['login'].' - Adres email: '.$row['email'].' - Data rejestracji: '.$row['data'].'</p>';
		}
	}
	
	//Dodawanie nowego użytkownika do bazy danych - przez zalogowanego użytkownika systemu - z poziomu administratora w systemie//
	if(isset($_POST['Zarejestruj2']))
	{
		if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['role']))
		{
			$login = $_POST['login'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];
			$role = $_POST['role'];
			$data = $_POST['data'];
			$data = date('Y-m-d H:i:s');
			
			if(strlen($login) < 3 || strlen($login) > 25)
			{
				echo 'Nieprawidłowa długość w wartości <b>Login</b>. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-dodaj-edytuj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else if(strlen($password) < 3 || strlen($password) > 25)
			{
				echo 'Nieprawidłowa długość w wartości <b>Hasło</b>. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-dodaj-edytuj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else if($password2 != $password)
			{
				echo 'Pola wartości <b>Hasło</b> i <b>Powtórz hasło</b> są różne. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-dodaj-edytuj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else if(preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]$/', $email))
			{
				echo 'Nieprawidłowa długość w wartości <b>Email</b>. Użyto niedozwolonych znaków. <br />';
				
				//Przekierowanie na stronę główną samodzielnej rejestracji konta
				header('Refresh: 3; URL=logowanie-dodaj-edytuj.php');
				echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
			}
			else
			{
				$result = "INSERT INTO logowanie (login, password, email, role, data) VALUES('$login', '$password', '$email', 'role', '$data')";
				if(mysqli_query($link,$result))
				{
					echo 'Użytkownik został zarejestrowany w systemie <br /><br />';
					echo 'Zapisane dane: <br />
					  Podana nazwa <b>[Login]</b>: <b>'.$login.'</b><br />
					  Podane hasło <b>[Hasło]</b>: <b>'.$password.'</b><br />
					  Podany adres e-mail <b>[Email]</b>: <b>'.$email.'</b><br />
					  
					  Dane zapisano z datą: <b>'.$data.'</b><br />';
				
					//Przekierowanie na stronę główną
					header('Refresh: 10; URL=logowanie-baza.php');
					echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
				}
				else
				{
					echo "Błąd: " . $result . "<br>" . mysqli_error($link);
				}	
			}
		}
		else
		{
			echo 'Błąd rejestracji';
		}
	}
	
	//Dane do aktualizacji
	$login = "";
	$password = "";
	$email = "";
	$role = "";
	$uid = 0;
	$edit_state = false;
	
	//Aktualizacja danych użytkownika
	if(isset($_POST['update']))
	{
		$login = mysqli_real_escape_string($link,$_POST['login']);
		$password = mysqli_real_escape_string($link,$_POST['password']);
		$id = mysqli_real_escape_string($link,$_POST['id']);
		$email = mysqli_real_escape_string($link,$_POST['email']);
		$role = mysqli_real_escape_string($link,$_POST['role']);
		mysqli_query($link, "UPDATE logowanie SET login='$login', password='$password', email='$email', role='$role' WHERE id='$id'");
		
		//Potwierdzenie aktualizacji rekordu
		echo 'Dane użytkownika zostały zaktualizowane.<br /><br />';
		
		//Przekierowanie na stronę główną
		header('Refresh: 10; URL=logowanie-baza.php');
		echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
	}
	
	//Usunięcie użytkownika systemu
	if($_SESSION['zalogowany']=='true')
	{
		if(isset($_GET['usun']))
		{
			$id = $_GET['usun'];
			mysqli_query($link, "DELETE FROM logowanie WHERE id=$id");
			
			//Potwierdzenie usunięcia rekordu.
			echo 'Rekord został usunięty. <br /><br />';
			
			//Przekierowanie na stronę główną
			header('Refresh: 5; URL=logowanie-baza.php');
			echo 'Zaraz zostaniesz przekierowany na stronę główną serwisu...';
		}
	}
	
	//Wysłanie maila do jednej osoby
	if(isset($_GET['wyslij_list']))
	{
		$id = $_GET['wyslij_list'];
		$result = mysqli_query($link, "SELECT * FROM logowanie WHERE id='$id'");
		$row = mysqli_fetch_assoc($result);
		$login = $row['login'];
		$email = $row['email'];
		
		//Wysyłanie przykładowego maila do zapisanego odbiorcy
		$tresc = "Imie: $login \n <br />. Wiadomość z serwisu logowanie.pl.";
		$header =  "From: A L \nContent-Type:".
				   ' text/plain;charset="UTF-8"'.
				  "\nContent-Transfer-Encoding: 8bit";
				
		mail($email, 'Kontakt ze strony www.logowanie.pl', $tresc, $header);
		
		//Potwierdzenie wysłania maila
		echo 'Wiadomość do użytkownika <b>'.$login.'</b> z serwisu logowanie.pl została wysłana. <br />';
		
		//Przekierowanie na stronę główną
		header('Refresh: 5; URL=logowanie-baza.php');
		echo 'Zaraz zostaniesz przekierowany na stronę główną seriwsu.';

	}
	
	
	//Przypominanie hasła - dla niezalogowanej oosby
	//Do sprawdzenia -  do sprawdzenia
	/*
	$danekonta=mysql_query("SELECT password,email,username FROM users1 WHERE username='$_POST[username] AND email='$_POST[email]'") or die(mysql_error());
	$pdanekonta=mysql_fetch_array($danekonta);

	if(isset($_POST['submit']))
	{
	$error1='';
	 
	if (! ereg ("^.+@.+..+$", $_POST[email])) $error1.='Podano niepoprawny adres e-mail!<br>'; //preg_match
	if (strlen($_POST['email'])<1) $error1.='Nie wpisałeś adresu e-mail!<br>';
	if (strlen($_POST['username'])<1) $error1.='Nie wpisałeś loginu!<br>';
	if ($pdanekonta[username] == NULL) $error1.='Nie ma takiego konta!<br>';
	if ($pdanekonta[email] != $_POST['email']) $error1.='Zły adres e-mail!<br>';
	 
		if ($error1!='') 
		echo "<p class='error'>$error1</p>";
		else {
		
		$haslo = rand(1000,9999);
		$haslomd5 = md5($haslo);
			mysql_query("UPDATE `users1` SET `password`='$haslomd5' WHERE `username`='$_POST[username]'") or die(mysql_error());
			echo "<p class='ok>Hasło zostało wysłane na e-mail: $_POST[email]</p>";        
			 }
	}
	*/
?>