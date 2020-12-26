<?php
	include('../functions.php');
	include('../administrator_credentials.php');

	session_start();


	
	if (isset($_GET['email']) && isset($_GET['token'])) {
		

		$email = $_GET['email'];
		$token = $_GET['token'];
		$query = "SELECT * FROM `login` WHERE `email` =:email AND `token` = :token AND `tokenExpire` > NOW() + INTERVAL 3 HOUR";
		$statement = $connect->prepare($query);
		
		$statement->execute(
			array(
				':email' => $email,
				':token' => $token
			)
		);
		$count = $statement->rowCount();
		
		if ($count > 0) {
			$newPassword = generateNewString();
			$newPasswordEncrypted = password_hash($newPassword, PASSWORD_DEFAULT);

		

			$query = "UPDATE `login` SET `token`='', `password` = :password WHERE `email`=:email";
			$statement = $connect->prepare($query);
			
			$statement->execute(
				array(
					':password' => $newPasswordEncrypted,
					':email' => $email
				)
			);
		
			echo "Your New Password Is $newPassword<br><a href='/chat/home'>Click Here To Log In</a>";
		} else
			redirectToLoginPage();
	} else {
		redirectToLoginPage();
	}
?>
