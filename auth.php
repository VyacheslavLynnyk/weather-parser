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
	<title>Auth</title>
</head>
<body>

	<div style="width: 320px; margin: 200px auto; text-align: center">
		<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">

			<h1 style="color: #999;font-family: Arial,sans-serif;">Enter the password:</h1>
	
			<input type="password" name='pass' required="required">		
			<button type="submit">Enter</button>					
		</form>
	</div>

</body>
</html>