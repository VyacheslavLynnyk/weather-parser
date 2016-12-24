<?php 
if (isset($_COOKIE["password"]) && sha1($_COOKIE["password"]) == '453407e93d72014d648ec95503217423ca7d13b0') {
  header('LOCATION: /index.php');
  exit;
}
if (isset($_POST['pass']) && sha1($_POST['pass']) == '1f1335fb0a6f530f9104532f65602f3a78e0a2f0') {
	setcookie("password",sha1($_POST['pass']) ,time()+ 3600*24);
	header('LOCATION: /index.php');
	exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Auth</title>
	<link rel="stylesheet" href="./styles/main.css">
</head>
<body>

	<div style="width: 380px; margin: calc(50vh - 210px) auto; text-align: center">
		<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" style="text-align: center">
			<img src="imgs/weather-logo.png" alt="weather">
			<img src="imgs/enter.png" alt="enter">
			<input type="password" name='pass' required="required" autofocus>
			<button type="submit">Войти</button>
		</form>
	</div>

</body>
</html>