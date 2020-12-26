<?php

include('connection/database_connection.php');

session_start();



$message = '';


if(!$_GET['message']){
    $message = "<label>Registration Completed</label>";

}else{
    $message = '';
}

if(isset($_SESSION['user_id']))
{
	header('location:index.php');
}

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
		
		
	
		if($count > 0){
		
			$query = 'UPDATE `login` SET `confirmation` =:confirmation, `token` =:token, `tokenExpire` =:tokenExpire WHERE `email` = :email';
			$statement = $connect->prepare($query);
			$statement->execute(
				array(
					':confirmation' => 1,
					':token' => '',
					':tokenExpire' => '',
					':email' => $email
				)
			);
			

			
		}

}



if($_POST['login'] = 'login' && $_POST['username'] != '' && $_POST['password'] != '')
{

	
	$query = "
		SELECT * FROM login 
  		WHERE username = :username AND confirmation =:confirmation
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':username' => $_POST["username"],
			':confirmation' => 1
		)
	);	
	$count = $statement->rowCount();

	if($count > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			if(password_verify($_POST["password"], $row["password"]))
			{
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['username'] = $row['username'];
				$sub_query = "
				INSERT INTO login_details 
	     		(user_id) 
	     		VALUES ('".$row['user_id']."')
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$_SESSION['login_details_id'] = $connect->lastInsertId();
				header('location:index.php');
			}
			else
			{
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array('password' => 'Wrong password')));
			}
		}
	}
	else
	{
		
		header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'Username does not exist or not confirmed')));

		
	}


}else{ 
	header('location:home');
}


?>
