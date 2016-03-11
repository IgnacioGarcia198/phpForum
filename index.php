<?php
	include('header.php');
?>
<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title></title>
<body>
	<div id="header"><h1>Message Board</h1></div>
	
	<div id="login">
		<p>
			<?php
			// bienvenida al usuario y decimos su nombre si está logeado
				echo '<h4>Welcome';
				if (isset($_SESSION['first_name'])) {
					echo ", {$_SESSION['first_name']}!";
				}				
				echo '</h4>';
				//mostramos los links de login o logout según el estado del usuario
				if(isset($_SESSION['user_id']) && //esta logeado
				(substr($_SERVER['PHP_SELF'], -10) != 'logout.php')) {
					echo '<a href="logout.php">Logout</a><br />
					<a href="change_password.php">Change Password</a><br />';
				}
				else { // no esta logeado
					echo '<a href="register.php">Register</a><br />
					<a href="login.php">Login to your account</a><br />
					<a href="forgot_password.php">Forgot Password</a><br />';
				}
			?>
		</p>
	</div>
	
	<div id="lypsum">
	<?php
		require_once("configmsgbrd.php");
		if (isset($_POST['submitted'])) { // si le han dado al boton de enviar, 
		// manejamos los resultados obtenidos por el formulario:
			// first name valid?
			if(preg_match('%^[-_a-zA-Z]{2,20}$%', 
			stripslashes(trim($_POST['firstname'])))) {
				$fn = escape_data($_POST['firstname']);
			}
			else{
				$fn = FALSE;
				echo '<p><font color="red" size="+1">Please enter a valid first name</font></p>';
			}
			
			// last name valid?
			if(preg_match('%^[-_a-zA-Z]{2,30}$%', 
			stripslashes(trim($_POST['lastname'])))) {
				$ln = escape_data($_POST['lastname']);
			}
			else{
				$ln = FALSE;
				echo '<p><font color="red" size="+1">Please enter a valid last name</font></p>';
			}
			
			// email valid?
			if (preg_match ('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', 
			stripslashes(trim($_POST['email'])))) {
				$e = escape_data($_POST['email']);
			}
			else{
				$e = FALSE;
				echo '<p><font color="red" size="+1">Please enter a valid email</font></p>';
			}
			
			// username valid?
			if (preg_match ('/^[A-Za-z][A-Za-z0-9]{5,31}$/',
			(stripslashes(trim($_POST['userid']))))) {
				$ui = escape_data($_POST['userid']);
			}
			else{
				$ui = FALSE;
				echo '<p><font color="red" size="+1">Please enter a valid user name</font></p>';
			}
			
			// password valid?
			$pass = stripslashes(trim($_POST['password1']));
			$uppercase = preg_match('@[A-Z]@', $pass);
			$lowercase = preg_match('@[a-z]@', $pass);
			$number = preg_match('@[0-9]@', $pass);
			if($uppercase && $lowercase && $number && strlen($pass) > 5) {
				if(($_POST['password1'] == $_POST['password2']) &&
				($_POST['password1'] != $_POST['userid'])) {
					$p = escape_data($_POST['password1']);
				}
				elseif($_POST['password1'] == $_POST['userid']) {
					$p = FALSE;
					echo '<p><font color="red" size="+1">Your password cannot be the same 
					as the userid</font></p>';
				}
				else{
					$p = FALSE;
					echo '<p><font color="red" size="+1">
					Your password does not match the confirmed password</font></p>';
				}
			}
			else{
				$p = FALSE;
				echo '<p><font color="red" size="+1">Please enter a valid password</font></p>';
			}
			
			// PHP code for the Captcha System
			$captchchk = 1;
			require_once('./includes/recaptchalib.php');
			$privatekey = "PRIVATE KEY HERE";
			$resp = recaptcha_check_answer($privatekey,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);
			if(!$resp->is_valid) { //captcha incorrecto
				echo '<p><font color="red" size="+1">The captcha is wrong</font></p>';
				$captchchk = 0;
			}
			
			if($fn && $ln && $e && $p && $ui && $captchchk) {
			// nos aseguramos de que el usuario no esta ya registrado con ese nombre
				$query = "SELECT username FROM users WHERE username='$ui'";
				$result = mysqli_query($query) or trigger_error("Sorry there 
				is no an account assigned to that userid");
				if(mysqli_num_rows($result) == 0) { //no hay ninguno
				// aqui hacemos cosas raras para crear la clave de activacion
					$a = md5(uniqid(rand(), true));
					// metemos al usuario en la base de datos
					$query = "INSERT INTO users (first_name, last_name, email, passwd, active, username)
					VALUES ('$fn', '$ln', '$e', SHA('$p'), '$a', '$ui')";
					$result = mysqli_query($query) or trigger_error(
					"Sorry an error occured and the account could not be created.");
					if(mysqli_affected_rows() == 1) {
					//comprobamos que ha funcionado bien, solo afecto a una fila
						$body ="Thank you for registering. To activate account click this link:<br />";
						$body .= "http://localhost/foro2/mbactivate.php?x=" .mysqli_insert_id()."$y=$a";
						mail($POST['email'], 'Registration Confirmation', $body, 
						'From: nash.makineta@gmail.com');
						
						echo '<br /><br /><h3>Thank you for registering, a confirmation 
						email has been sentto your address.</h3>';
						exit();
					}
					else {
						echo '<p><font color="red" size="+1">Lo sentimos, 
						no pudiste ser registrado debido a un error del sistema</font></p>';
					}
				}
				else { // el email ya esta registrado
					echo '<p><font color="red" size="+1">El email ya esta registrado.
					Si has olvidado la contraseña, usa el link para que te la enviemos.</font></p>';
				}	
			}
			else { // alguno de los test ha fallado:
				echo '<p><font color="red" size="+1">Please try again.</font></p>';
			}
			mysqli_close();
		}	
	?>
	<h1<Register</h1>
	<form action="mbregister.php" method="post">
		<fieldset>
			<p><b>Last name:</b><input type="text" name="lastname" size="30"
			maxlength="30" value="<?php if(isset($_POST['lastname'])) echo 
			$_POST['lastname']; ?>" /></p>
			
			<p><b>Address:</b><input type="text" name="email" size="40"
			maxlength="40" value="<?php if(isset($_POST['email'])) echo 
			$_POST['email']; ?>" /></p>
			
			<p><b>Username:</b><input type="text" name="userid" size="20"
			maxlength="20" /><small>Must contain a letter of both cases, a number and a
			minimum length of 8 characters.</small></p>
			
			<p><b>Password:</b><input type="password" name="password1" size="20"/>
			<small>Must contain a letter of both cases, a number and a
			minimum length of 8 characters.</small></p>
			
			<p><b>Confirm Password:</b><input type="password" name="password2" size="20"/>
			</p>
			<?php
				require_once('recaptchalib.php');
				$publickey="PUBLIC KEY HERE";
				echo recaptcha_get_html($publickey);
			?>
		</fieldset>
		<div align="center"><input type="submit" name="submit" value="Register"
		/></div>
		
		<input type="hidden" name="submitted" value="true" />
	</form>
	</div>
	</div>

	<div id="footer"><h2>This is the Footer</h2></div>
</body>
</html>