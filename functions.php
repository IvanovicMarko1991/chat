<?php

include('connection/database_connection.php');


	function generateNewString($len = 10) {
		$token = "poiuztrewqasdfghjklmnbvcxy1234567890";
		$token = str_shuffle($token);
		$token = substr($token, 0, $len);

		return $token;
	}

	function redirectToLoginPage() {
		header('Location: index.htm');
		exit();
	}

	function getUsersType($connect){

	

		$categories_user = 'SELECT `user_type` FROM `user_type` WHERE `is_active` =:is_active';
		$statement = $connect->prepare($categories_user);

		$statement->execute(
			array(
				':is_active' => 1
			)
		);

		$results = $statement->fetchAll();

		return $results;
	}


	function getCategories($connect){

		$categories_query = 'SELECT `faculty` FROM `categories` WHERE `is_active` =:is_active';
		$statement = $connect->prepare($categories_query);

		$statement->execute(
			array(
				':is_active' => 1
			)
		);

		$results = $statement->fetchAll();

		return $results;

	}

?>
