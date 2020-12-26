<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include('../functions.php');
    include('../administrator_credentials.php');
    session_start();

   
    if (isset($_POST['email'])) {
       
       
        $email = $_POST['email'];

        $query = "SELECT * FROM login WHERE email= :email";

        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':email' => $email
            )
        );
        $count = $statement->rowCount();
        $data = $statement->fetch();
       
        if ($count > 0) {

            $token = generateNewString();

	        $query = "UPDATE login SET token=:token, 
            tokenExpire=DATE_ADD(NOW() + INTERVAL 3 HOUR, 
            INTERVAL 5 MINUTE) WHERE email=:email";

            $statement =$connect->prepare($query);
            $statement->execute(
                array(
                    ':token' => $token,
                    ':email' => $email
                )
            );


            require_once "../PHPMailer/src/PHPMailer.php";
            require_once "../PHPMailer/src/SMTP.php";
            require_once "../PHPMailer/src/Exception.php";
            
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
            $mail->Subject = "Reset Password";
            // $mail->SMTPDebug = 3;
            $mail->Body = "
                Hi,<br><br>
                
                In order to reset your password, please click on the link below:<br>
                <a href='
                http://192.168.64.3/chat/register/resetPassword.php?email=$email&token=$token
                '>http://192.168.64.3/chat/register/resetPassword.php?email=$email&token=$token</a><br><br>
                
                Kind Regards,<br>
                Tibiscus Faculty
            ";
        
            if ($mail->send()){ 
                exit(json_encode(array("status" => 1, "msg" => 'Please Check Your Email Inbox!')));
            }else{
                exit(json_encode(array("status" => 0, "msg" => 'Something Wrong Just Happened! Please try again!')));
            }
          
        } 
    }else{ 
        exit(json_encode(array("status" => 0, "msg" => 'Please Check Your Inputs!')));
    }
?>
