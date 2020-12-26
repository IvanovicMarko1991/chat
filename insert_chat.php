<?php
include('connection/database_connection.php');

session_start();


if(!empty($_POST) && empty($_FILES)){ 

	$data = array(
		':to_user_id'		=>	$_POST['to_user_id'],
		':from_user_id'		=>	$_SESSION['user_id'],
		':chat_message'		=>	$_POST['chat_message'],
		':status'			=>	'1'
	);

	$query = "
	INSERT INTO chat_message 
	(to_user_id, from_user_id, chat_message, status) 
	VALUES (:to_user_id, :from_user_id, :chat_message, :status)
	";

	$statement = $connect->prepare($query);

	if($statement->execute($data))
	{
		echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
	}
}

if(!empty($_FILES) && !empty($_POST)){
	

	
	if(is_uploaded_file($_FILES['file']['tmp_name']))
	{
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$allow_ext = array('jpg', 'png', 'jpeg');

		
		if(in_array($ext, $allow_ext))
		{
			$_source_path = $_FILES['file']['tmp_name'];	
			$target_path = 'upload/' .iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE',$_FILES['file']['name']);
			
		try{	
			move_uploaded_file($_source_path, $target_path);

		}catch(Exception $e){
			 return json_encode('File upload failed:'. $e->getMessage());
			
		}
	
			$data = array(
				':to_user_id'		=>	$_POST['to_user_id'],
				':from_user_id'		=>	$_SESSION['user_id'],
				':chat_message'		=>	'<p><img src="'.$target_path.'" class="img-thumbnail" width="200" height="160" /></p><br />',
				':status'			=>	'1'
			);

			$query = "
			INSERT INTO chat_message 
			(to_user_id, from_user_id, chat_message, status) 
			VALUES (:to_user_id, :from_user_id, :chat_message, :status)
			";
		
			$statement = $connect->prepare($query);

			if($statement->execute($data))
			{
				echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
			}else{
				http_response_code(500);
				echo json_encode(array('error' => 'Interneal error,please retry'));
			}

		}else {
			http_response_code(415);
			echo json_encode(array('error' => 'We do not support this type of files'));
		}
	
	}
}
?>
