<?php
include('../functions.php');
include('../administrator_credentials.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$message = '';


$username = trim($_POST["username"]);
$email = trim($_POST['email']);
$password = trim($_POST["password"]);
$faculty  = trim($_POST["faculty"]);
$member_type = trim($_POST["member_type"]);
$check_query = "
SELECT * FROM login 
WHERE username = :username
";

$statement = $connect->prepare($check_query);


$check_data = array(
	':username'		=>	$username
);


if($statement->execute($check_data))	
{
	


	if($statement->rowCount() > 0)
	{
		echo json_encode(array('fail' => 'Username or email already taken'));
		
	}
	else
	{

		
		
		if($message == '')
		{
			$data = array(
				':username'		=>	$username,
				':email'        =>  $email,
				':password'		=>	password_hash($password, PASSWORD_DEFAULT),
				':faculty'      => $faculty,
				':member_type'  => $member_type
			);


		


			$query = "
			INSERT INTO login 
			(username, email, password, faculty, member_type) 
			VALUES (:username, :email,  :password, :faculty, :member_type)
			";
			$statement = $connect->prepare($query);
			
			if($statement->execute($data))
			{

				require_once "../PHPMailer/src/PHPMailer.php";
				require_once "../PHPMailer/src/SMTP.php";
				require_once "../PHPMailer/src/Exception.php";

				$token = generateNewString();

				$query = "UPDATE login SET token=:token, 
				tokenExpire=DATE_ADD(NOW() + INTERVAL 12 HOUR, 
				INTERVAL 5 MINUTE) WHERE email=:email";
	
				$statement =$connect->prepare($query);
				$statement->execute(
					array(
						':token' => $token,
						':email' => $email
					)
				);


				$mail = new PHPMailer(true);
            
            $mail->isSMTP();
            $mail->Mailer = 'smtp';
            
            $mail->Host  = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
			
            $mail->Username = $emailAdministrator;
            $mail->Password = $passwordAdministrator;
            $mail->isHTML(true);
            $mail->addAddress($email);
            $mail->setFrom($emailAdministrator, 'Administrator ChatApp');
            $mail->Subject = "Registration confirmation";
            // $mail->SMTPDebug = 3;
            $mail->Body = "
                Hi,<br><br>
                
                To confirm the registration please enter the link bellow<br>
                <a href='
                http://192.168.64.3/chat/login.php?email=$email&token=$token
                '>http://192.168.64.3/chat/login.php?email=$email&token=$token</a><br><br>
                
                Kind Regards,<br>
                Tibiscus Faculty
            ";
        
            if ($mail->send()){ 
                exit(json_encode(array('success' => 'Registration complete.Please check your email for confirmation.')));
            }else{
                exit(json_encode(array("fail" => 'Something Wrong Just Happened! Please try again!')));
            }
				
			}
		}
	}

	
}


?>
