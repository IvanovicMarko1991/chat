<?php

include 'connection/database_connection.php';

session_start();
$selected_sort = $_POST['selected'];

if ($selected_sort == 'A-Z') {

    $query = "SELECT * FROM `login` WHERE `user_id` !=".$_SESSION['user_id']." ORDER BY `username` ASC";
    

    $statement = $connect->prepare($query);


    $statement->execute();

    $result = $statement->fetchAll();



}elseif ($selected_sort == 'Z-A') {
    $query = "SELECT * FROM `login` WHERE `user_id` !=".$_SESSION['user_id']." ORDER BY `username` DESC";

    $statement = $connect->prepare($query);

    $statement->execute();

    $result = $statement->fetchAll();

}elseif ($selected_sort != 'A-Z' || $selected_sort != 'Z-A') {
    $query = "SELECT * FROM `login` WHERE `faculty` =   '$selected_sort' AND `user_id` != ".$_SESSION['user_id'];

    $statement = $connect->prepare($query);

    $statement->execute();

    $result = $statement->fetchAll();

}

$output = '
<table class="table table-bordered table-striped">
	<tr class="table100-head">
		<th width="30%">Username</th>
		<th width="20%">Member-Type</th>
		<th width="20%">Faculty</th>
		<th width="20%">Status</th>
		<th width="10%">Action</th>
	</tr>
';

foreach ($result as $row) {
    

    $status = '';
    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
    $user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
    if ($user_last_activity > $current_timestamp) {
        $status = '<span class="label label-success">Online</span>';
    } else {
        $status = '<span class="label label-danger">Offline</span>';

    }

    if ($user_last_activity == '') {
        $user_last_activity == '';
    } else {
        $user_last_activity = '<span>Last seen online..' . $user_last_activity . '</span>';
    }

    $output .= '
	<tr>
		<td>' . $row['username'] . ' ' . count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect) . ' ' . fetch_is_type_status($row['user_id'], $connect) . '</td>
		<td>' . $row['member_type'] . '</td>
		<td>' . $row['faculty'] . '</td>
		<td>' . $status . '<br>' . $user_last_activity . '</td>
		<td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="' . $row['user_id'] . '" data-tousername="' . $row['username'] . '">Start Chat</button></td>
	</tr>
    ';
   
}

$output .= '</table>';


echo $output;
