<?php
	Session_start();
	$link = mysqli_connect("localhost", "root", "", "testowa");
	
	//Nie pokazuje denerwujących błędów i bugów
	error_reporting(E_ALL & ~E_NOTICE);
?>

<!DOCTYPE html>
<html lang="pl-PL">
	<head>
		<meta name="keyword" content="Strony na GITHub" />
		<meta name="description" content="Strony na GITHub" />
		<title>Strony i projekty na GITHuba</title>
	</head>
	<body>
		<h3>System logowania - Wyniki wyszukiwania</h3>
		
		<div style="width: 900px; border: 1px solid #cccccc; margin: 5px; padding: 5px;">

			<?php
			
				echo 'Wyniki wyszukiwania: <br /><br />';
				if(isset($_POST['szukaj']))
				{
					if(isset($_POST['fraza']))
					{
						$fraza = $_POST['fraza'];
					}
					$results = mysqli_query($link,"SELECT login, email FROM logowanie WHERE login LIKE '%{$fraza}%' OR email LIKE '%{$fraza}%'");
					while($row = mysqli_fetch_array($results))
					{
						echo ' - '.$row['login'].' - '.$row['email'].'<br />';
					}
				}
				
			?>
			
			<p>Wróć do strony <a href="logowanie-baza.php" title="Przejdź do strony głównej">Głównej</a></p>
		</div>
	</body>
</html>