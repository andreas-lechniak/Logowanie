<?php
	
	//Session_start();
	$link = mysqli_connect("localhost", "root", "", "testowa");
	include('logowanie-serwer.php');
	
	//Nie pokazuje denerwujących błędów i bugów
	error_reporting(E_ALL & ~E_NOTICE);
	
	//Do aktualizacji rekordu
	if(isset($_GET['edycja']))
	{
		$id = $_GET['edycja'];
		$update = true;
		$record = mysqli_query($link, "SELECT * FROM logowanie WHERE id=$id");
		if (@count($record) == 1)
		{
			$n = mysqli_fetch_array($record);
			$login = $n['login'];
			$password = $n['password'];
			$email = $n['email'];
			$role = $n['role'];
		}
	}
	
?>

<!DOCTYPE html>
<html lang="pl-PL">
	<head>
		<meta name="keyword" content="Strony na GITHub" />
		<meta name="description" content="Strony na GITHub" />
		<title>Strony i projekty na GITHuba</title>
	</head>
	<body>
		<h3>System logowania - Dodawanie i edycja użytkowników</h3>
		
			<div style="width: 900px; border: 1px solid #cccccc; margin: 5px; padding: 5px;">

			<?php
				//Formularz dodania i edycji użytkownika w strefie zablokowanej
				echo '<h4>Dodaj lub edytuj użytkownika</h4>';
			?>

			<form action="logowanie-serwer.php" method="POST">
				<p>
					Użytkownik zostanie dodany do bazy / edytowany z dzisiejszą datą: <b><?php echo date("Y-m-d"); ?></b> <br /><br />
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					Nazwa loginu: <input type="text" name="login" id="login" placeholder="Podaj nazwę" value="<?php echo $login; ?>"> <span style="font-size:11px;">Format - <b>Nazwa</b></span>
					<br /><br />
					Twoje hasło: <input type="password" name="password" id="password" placeholder="Podaj hasło" value="<?php echo $password; ?>"> <span style="font-size:11px;">Format - <b>123Hasło</b></span>
					<br /><br />
					Potwierdź hasło: <input type="password" name="password2" id="password2" placeholder="Potwierdź hasło"> <span style="font-size:11px;">Format - <b>123Hasło</b></span>
					<br /><br />
					Twój e-mail: <input type="text" name="email" id="email" placeholder="Podaj adres email" value="<?php echo $email; ?>"> <span style="font-size:11px;">Format - <b>adres@adres.pl</b></span>
					<br /><br />
								 <input type="hidden" name="data">
					Rola: <input type="text" name="role" id="role" placeholder="Wpisz 0 lub 1" value="<?php echo $role; ?>"> <span style="font-size:11px;">Format - <b>0</b> lub <b>1</b></span>
				</p>
				<?php if ($update == true): ?>
				<p>Edycja rekordu o nr ID: <b><?php echo $id; ?></b>.</p>
				<input type="submit" name="update" value="Uaktualnij">
				<?php else: ?>
				<input type="submit" name="Zarejestruj2" value="Rejestruj">
				<input type="reset" value="Wyczyść">
				<?php endif ?>
			</form>

		</div>
	</body>
</html>