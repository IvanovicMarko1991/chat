<?php

include 'functions.php';

session_start();

$faculties = getCategories($connect);


if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
}

// $options = mysql_query('SELECT * FROM `faculties` WHERE `is_active` = 1');

?>

<html>
    <head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Chat Messenger Tibiscus</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="https://cdn.rawgit.com/mervick/emojionearea/master/dist/emojionearea.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
        <div class="container">
			<br />

			<h3 id="title_faculty" align="center">Tibiscus Chat Application</h3><br />
			<p id="welcome">Hello,  <?php echo $_SESSION['username']; ?></p>
			<br />
			<button class="update-button" id="update">Update</button>
			<div class="row">
				<div class="col-md-8 col-sm-6">
					
					
                    <label>Sort By</label>

                    <select name="sort" id="selected-sort" onchange="selectedSort()">
                        <option name="last_seen">Last seen online...</option>
                        <option name="a_z">A-Z</option>
                        <option name="z_a">Z-A</option>
						<?php

							foreach($faculties as $category){
								echo '<option name="'.$category['faculty'].'">'.$category['faculty'].'</option>';
							}
                        
                        ?>
                    </select>
				</div>
				<div class="col-md-2 col-sm-3">
					<input type="hidden" id="is_active_group_chat_window" value="no" />
					<button type="button" name="group_chat" id="group_chat" class="btn btn-warning btn-xs">Group Chat</button>
				</div>
				<div class="col-md-2 col-sm-3">
					<p align="right"> <a class="btn btn-logout" href="logout.php">Logout</a></p>
				</div>
			</div>
			<div class="table-responsive">

			<h4>List of Users</h4>
				    <div id="user_details"></div>
				    <div id="user_model_details"></div>

			</div>
			<br />
			<br />

		</div>

    </body>
</html>


<div id="group_chat_dialog" title="Group Chat Window">
	<div id="group_chat_history" style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;">

	</div>
	<div class="form-group">
		<div class="chat_message_area">
			<div id="group_chat_message" contenteditable class="form-control">

			</div>
			<div class="image_upload">
				<form id="uploadImage" method="post" action="upload.php">
					<label for="uploadFile"><img src="upload.png" /></label>
					<input type="file" name="uploadFile" id="uploadFile" accept=".jpg, .png" />
				</form>
			</div>
		</div>
	</div>
	<div class="form-group" align="right">
		<button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info">Send</button>
	</div>
</div>


<script src="js/script.js"></script>
<script src ="js/sort.js"></script>
