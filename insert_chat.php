<?php
include('connection/database_connection.php');

session_start();

// Check if the POST data is not empty and no files were uploaded
if(!empty($_POST) && empty($_FILES)) { 

	// Prepare the data to be inserted into the database
	$data = array(
		':to_user_id'		=>	$_POST['to_user_id'],
		':from_user_id'		=>	$_SESSION['user_id'],
		':chat_message'		=>	$_POST['chat_message'],
		':status'			=>	'1'
	);

	// Prepare the SQL query to insert the chat message
	$query = "
		INSERT INTO chat_message 
		(to_user_id, from_user_id, chat_message, status) 
		VALUES (:to_user_id, :from_user_id, :chat_message, :status)
	";

	$statement = $connect->prepare($query);

	// Execute the query and return the chat history
	if($statement->execute($data)) {
		echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
	}
}

// Check if both POST data and files were submitted
if(!empty($_FILES) && !empty($_POST)){

	// Check if the uploaded file is an image file with an allowed extension
	if(is_uploaded_file($_FILES['file']['tmp_name'])) {
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$allow_ext = array('jpg', 'png', 'jpeg');

		if(in_array($ext, $allow_ext)) {
			// Define the target path for the uploaded file
			$_source_path = $_FILES['file']['tmp_name'];	
			$target_path = 'upload/' .iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE',$_FILES['file']['name']);

			try {
				// Move the uploaded file to the target path
				move_uploaded_file($_source_path, $target_path);
			} catch(Exception $e) {
				// Return an error message if the file upload failed
				http_response_code(500);
				echo json_encode(array('error' => 'File upload failed:'. $e->getMessage()));
				exit();
			}

			// Prepare the data to be inserted into the database
			$data = array(
				':to_user_id'		=>	$_POST['to_user_id'],
				':from_user_id'		=>	$_SESSION['user_id'],
				':chat_message'		=>	'<p><img src="'.$target_path.'" class="img-thumbnail" width="200" height="160" /></p><br />',
				':status'			=>	'1'
			);

			// Prepare the SQL query to insert the chat message
			$query = "
				INSERT INTO chat_message 
				(to_user_id, from_user_id, chat_message, status) 
				VALUES (:to_user_id, :from_user_id, :chat_message, :status)
			";
		
			$statement = $connect->prepare($query);

			// Execute the query and return the chat history or an error message
			if($statement->execute($data)) {
				echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
			} else {
				http_response_code(500);
				echo json_encode(array('error' => 'Internal error, please retry'));
			}

		} else {
			// Return

			http_response_code(415);
			echo json_encode(array('error' => 'We do not support this type of files'));
		}
	
	}
}
?>
