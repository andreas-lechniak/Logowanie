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
		<h3>System logowania - Strefa dla zalogowanych użytkowników</h3>
		
		<div style="width: 900px; border: 1px solid #cccccc; margin: 5px; padding: 5px;">

			<?php
			
				$results = mysqli_query($link,"SELECT * FROM logowanie");
				echo '<div>
					  Udało się zalogować jako: '.$_SESSION['role'].' | ZALOGOWANY: '.$_SESSION['login'].' |
					  <a href="logowanie.php?wyloguj='.$_SESSION['login'].'" OnClick="return confirm(\'Czy na pewno chcesz się wylogować?\');" >Wyloguj</a><br /><br />
					  </div>';
				
				//Wyszukiwanie w zalogowanej części
				echo '<div style="text-align: right;">
						<form action="logowanie-szukaj.php" method="POST" target="_blank">
							Kogo szukasz: <input type="text" name="fraza" placeholder="Wpisz frazę"> <input type="submit" name="szukaj" value="Szukaj">
						</form>
					  </div>';
					  
				echo '<h4>Zarejestrowani użytkownicy mojego serwisu</h4>';

				//Wyświetlanie danych w zablokowanej strefie
				while($row = mysqli_fetch_array($results))
				{  
					if($_SESSION['role'] == 1 && ($row['id'] != $_SESSION['id'])) 
					{ 
						
						// Bez usuwania własnego konta - konto admina
						echo '<p>'.$row['login'].' - 
							  <a href="logowanie-serwer.php?usun='.$row['id'].'" OnClick="return confirm(\'Czy na pewno chcesz usunąć użytkownika?\');" target="_blank" title="Usuń rekord">Usuń użytkownika</a> | 
							  <a href="logowanie-dodaj-edytuj.php?edycja='.$row['id'].'" target="_blank" title="Edytuj dane użytkownika">Edytuj dane</a> | 
							  <a href="logowanie-serwer.php?pokaz='.$row['id'].'" target="_blank" title="Pokaż szczegóły użytkownika">Pokaż</a>';
					}

					if($_SESSION['role']==0)
					{
						if($row['login']=='admin')
						{
							echo 'Admin';
						}
						else
						{	
							echo '<p>'.$row['login'].' - <a href="logowanie-dodaj-edytuj.php?edycja='.$row['id'].'" target="_blank" title="Edytuj dane użytkownika">Edytuj dane</a>';
						}
					}

					if($row['role']==1)
					{
						echo ' - rola: Admin [Administrator] - adres e-mail: '.$row['email'].' - <a href="logowanie-serwer.php?wyslij_list='.$row['id'].'" title="Wyślij wiadomość" target="_blank">Wiadomość</a> <br />';
					}
					
					if($row['role']==0)
					{
						echo ' - rola: User [Użytkownik] - adres e-mail: '.$row['email'].'. - <a href="logowanie-serwer.php?wyslij_list='.$row['id'].'" title="Wyślij wiadomość" target="_blank">Wiadomość</a> <br />';
					}
					echo '</p>';
				}
				
				echo '<h4>Statystyki ilościowe</h4><p>';
				//Wyświetlanie ilości wszystkich NEWSÓW bez przypisanej jednej, konkretnej kategorii
				$results = mysqli_query($link, "SELECT count(id) AS ilosc FROM logowanie");
				$row = mysqli_fetch_array($results);
				$ile_uzytkownikow = $row['ilosc'];
				echo 'Ilość wszystkich zarejestrowanych użytkowników w bazie: <b>'.$ile_uzytkownikow.'</b><br />';
				
				//Ile jest Administratorów w bazie
				$results = mysqli_query($link, "SELECT count(id) AS ilosc FROM logowanie WHERE role='1'");
				$row = mysqli_fetch_array($results);
				$ile_adminow = $row['ilosc'];
				echo 'Zarejestrowanych Administratorów: <b>'.$ile_adminow.'</b> | ';
				
				//Ilu jest Użytkowników w bazie
				$results = mysqli_query($link, "SELECT count(id) AS ilosc FROM logowanie WHERE role='0'");
				$row = mysqli_fetch_array($results);
				$ile_userow = $row['ilosc'];
				echo 'zarejestrowanych Użytkowników: <b>'.$ile_userow.'</b></p>';
		
				//Inne funckje w systemie zależne od roli
				if($_SESSION['role']==1)
				{
					echo '<h3>Dane zalogowanego użytkownika</h3>';
					echo '<p style="font-size:12px;">Działania dla roli <b>ADMINISTRATOR</b>: Dodawanie użytkowników | Edycja danych | Usuwanie użytkowników | Wysyłanie wiadomości.</p>';
					echo '<p>Login: '.$_SESSION['login'].' - hasło: '.$_SESSION['password'].'
						  <a href="" OnClick="return confirm(\'Czy na pewno chcesz edytować dane?\');" target="_blank" title="Edytuj moje dane">Edycja danych</a><br /><br />';
					echo '<h4>Inne czynności</h4>';
					echo '<a href="logowanie-dodaj-edytuj.php" target="_blank" title="Dodaj nowe konto użytkownika">Załóż konto</a></p>';
				}
					
				if($_SESSION['role']==0)
				{
					echo '<h3>Dane zalogowanego użytkownika</h3>';
					echo '<p style="font-size:12px;">Działania dla roli <b>UŻYTKOWNIK</b>: Edycja danych | Wysyłanie wiadomości.</p>';
					echo '<p>Login: '.$_SESSION['login'].' - hasło: '.$_SESSION['password'].' |
						  <a href="" title="Edytuj moje dane">Edycja danych</a></p>';
				}
				
			?>

		</div>
	</body>
</html>