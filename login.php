<?php

session_start();
require_once 'forms/login.form.class.php';
require_once 'db.class.php';
require_once 'password.class.php';
require_once 'session.class.php';
require_once 'common/funlib.php';


$msg = '';

$db = new DB();
$form = new LoginForm($_POST);

if ($_POST) {
    if ($form->validate()) {
        $username = $db->escape($form->getUsername());
        $password = new Password( $db->escape($form->getPassword()) );

        $res = $db->query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' LIMIT 1");
        if (!$res) {
            $msg = 'No such user found or invalid password provided';
        } else {
            $user = $res[0]['username'];
            $id = $res[0]['id'];
            Session::set('user', $user, 'id', $id);
            header('location: index.php?msg=You have been logged in');
        }

    } else {
        $msg = 'Please fill in fields';
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <?php require_once 'templates/head.php';?>
    <!-- SEARCH SCRIPT -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>

<body>
<div class="wrapper">
<?php require_once 'templates/top-menu.php';?>
<h1>Login</h1>

<b><?=$msg; ?></b>

<form method="post" class="loginForm">
    Username: <input type="text" name="username" value="<?=$form->getUsername(); ?>"/> <br/><br/>
    Password: <input type="password" name="password"/> <br/><br/>
    <button type="submit" name="submit" class="btn btn-primary" value="login">Login</button>
</form>

    <div class="push"></div>
</div>
<?php require_once 'templates/footer.php';?>
</body>
</html>