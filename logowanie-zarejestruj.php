<!DOCTYPE html>
<html lang="pl-PL">
	<head>
		<meta name="keyword" content="Strony na GITHub" />
		<meta name="description" content="Strony na GITHub" />
		<title>Strony i projekty na GITHuba</title>
	</head>
	<body>
		<h3>Rejestracja nowego członka serwisu</h3>
		<div>
			<p>Zakładanie konta przez osobę nie zalogowaną i nie zarejestrowaną w serwisie.</p>
			<form action="logowanie-serwer.php" method="POST">
				<p>
					Nazwa loginu: <input type="text" name="login" id="login" placeholder="Podaj nazwę"> <span style="font-size:11px;">Format - <b>Nazwa</b></span>
					<br /><br />
					Twoje hasło: <input type="password" name="password" id="password" placeholder="Podaj hasło"> <span style="font-size:11px;">Format - <b>123Hasło</b></span>
					<br /><br />
					Potwierdź hasło: <input type="password" name="password2" id="password2" placeholder="Potwierdź hasło"> <span style="font-size:11px;">Format - <b>123Hasło</b></span>
					<br /><br />
					Twój e-mail: <input type="text" name="email" id="email" placeholder="Podaj adres email"> <span style="font-size:11px;">Format - <b>adres@adres.pl</b><span>
					<input type="hidden" name="data">
					<input type="hidden" name="role">
				</p>
				<input type="submit" name="Zarejestruj" value="Rejestruj">
				<input type="reset" value="Wyczyść">
			</form>
		</div>
	</body>
</html>