<?php
    // 328/login/login.php

    /*
     * DROP TABLE users;
        CREATE TABLE users (
            userid int(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            username varchar(20) NOT NULL,
            password varchar(40) NOT NULL,
            authlevel int(1) DEFAULT NULL
        );

        INSERT INTO users (username, password, authlevel) VALUES
             ('jshmo', sha1('shmo123'), 1),
             ('jdoe', sha1('doe456'), 2);
     */

    //Turn on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    //See where the user came from
    //echo $_SERVER['HTTP_REFERER'];

    //Check login
    /*
    var_dump($_POST);
    ["username"]=> string(5) "jshmo"
    ["password"]=> string(7) "shmo123"
    */

    //Initialize error flag
    $errFlag = false;

    //See if the login form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //Connect to DB
        require $_SERVER['DOCUMENT_ROOT']."/../config.php";
        //require "/home/tostrand/config.php";
        // /home/tostrand/public_html
        // /home/tostrand/config.php

        //Query the DB
        $sql = "SELECT * FROM users WHERE username = :un AND password = :pw";
        $sql2 = "INSERT INTO users (username, password, authlevel) 
                VALUES (:username, :password, :authlevel)";
        $statement = $dbh->prepare($sql);

        $un = $_POST['username'];
        $pw = sha1($_POST['password']);
        $statement->bindParam(':un', $un, PDO::PARAM_STR);
        $statement->bindParam(':pw', $pw, PDO::PARAM_STR);
        $statement->execute();
        $count = $statement->rowCount();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $authLevel = $row['authlevel'];

        //Successful login
        if ($count == 1) {

            //$user = new User(...);
            //$_SESSION['un'] = $user;

            $_SESSION['un'] = $un;

            //Send the user back where they came from
            if (isset($_SESSION['page'])) {
                $loc = $_SESSION['page'];
            } else {
                $loc = "index.php";
            }
            header("location: $loc");

        } else {
            //Set error flag
            $errFlag = true;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
    <style>
        .err {
            color: darkred;
        }
    </style>
</head>
<body>
<div class="container">

    <h1>Login Page</h1>

    <form action="#" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control"
                   id="username" name="username"
                   value="">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" >
        </div>

        <?php
            if ($errFlag) {
                echo '<p class="err">Login is incorrect</p>';
            }
        ?>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

</div>

<script src="//code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>