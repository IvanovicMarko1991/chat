<?php

include 'functions.php';

$faculties = getCategories($connect);
$user_type = getUsersType($connect);

?>


<html>

<head>
    <title>Chat Messenger Tibiscus</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <br />

        <h3 align="center">Tibiscus Chat Application</a></h3><br />
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">Chat Application Register</div>
            <div class="panel-body">
                <form method="post" action="register.php">
                    <div class="form-group">
                        <label>Enter Username</label>
                        <input type="text" id="username" name="username" class="form-control" />
                        <p class="alert-message" id="message_username"></p>
                    </div>
                    <div class="form-group">
                        <label>Enter Email</label>
                        <input type="email" id="email" name="email" class="form-control" />
                        <p class="alert-message" id="message_email"></p>
                    </div>
                    <div class="form-group">
                        <label>Member Type</label>
                        <br>
                        <select id="member_type" name="member_type">
                            <?php
                                foreach($user_type as $user){
                                    echo '<option name="'.$user['user_type'].'">'.$user['user_type'].'</option>';
                                }
                            ?>
                       
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Faculty</label>
                        <br>
                        <select id="faculty" name="faculty">
                            <?php
                                foreach($faculties as $category){
                                    echo '<option name="'.$category['faculty'].'">'.$category['faculty'].'</option>';
                                }
                            
                            ?>
                       
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Enter Password</label>
                        <input type="password" id="password" name="password" class="form-control" />
                        <p class="alert-message" id="message_password"><?php $message ?></p>
                    </div>
                    <div class="form-group">
                        <label>Re-enter Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" />
                        <p class="alert-message" id="message_confirm"></p>
                    </div>

                    <div class="form-group">
                        <input id="register" type="submit" name="register" class="btn btn-info" value="Register" />
                    </div>
                    
                </form>
            </div>
        </div>

        <div>
            <div class="col-md-12">
                <div class="col-md-3">
                    <img src="upload/fcia.jpg">
                </div>
                <div class="col-md-3">
                    <img src="upload/fdap-d.jpg">
                </div>
                <div class="col-md-3">
                    <img src="upload/fp.jpg">
                </div>
                <div class="col-md-3">
                    <img src="upload/fse-eai.jpg">
                </div>

            </div>
        </div>
    </div>
    <script src="js/register.js"></script>

</body>

</html>
