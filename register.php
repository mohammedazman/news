<?php

# register new users

session_start();
require_once 'forms/registration.form.class.php';
require_once 'db.class.php';
require_once 'password.class.php';
require_once 'common/funlib.php';

$msg = ''; # whether the user register successfully or not show the appropriate message

$db = new DB();
$form = new RegistrationForm($_POST);

if ($_POST) {
    if ($form->validate()) {
        $email = $db->escape($form->getEmail());
        $username = $db->escape($form->getUsername());
        $password = new Password( $db->escape($form->getPassword()) );

        $res = $db->query("SELECT * FROM users WHERE username = '{$username}'");
        if ($res) {
            $msg = 'Such user already exists!';
        } else {
            $db->query("INSERT INTO users (email, username, password) VALUES ('{$email}','{$username}','{$password}')");
            header('location: index.php?msg=You have been registered');
        }

    } else {
        $msg = $form->passwordsMatch() ? 'Please fill in fields' : 'Passwords don\'t match';
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <?php require_once 'templates/head.php';?>
    <!-- SEARCH SCRIPT -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>
<body>
<?php require_once 'templates/top-menu.php';?>
<div class="wrapper">
<h1>Register new user</h1>

<b><?=$msg; ?></b>

<br/>
<form method="post" class="loginForm">
    <div class="cell">Email:</div><input type="email" name="email" value="<?=$form->getEmail(); ?>"/><br>
    <div class="cell">Username:</div> <input type="text" name="username" value="<?=$form->getUsername(); ?>"/><br>
    <div class="cell">Password:</div><input type="password" name="password"/><br>
    <div class="cell">Confirm password:</div><input type="password" name="passwordConfirm"/><br><br>
    <button type="submit" name="submit" class="btn btn-primary" value="register">Register</button>

</form>
    <div class="push"></div>
</div>
<?php require_once 'templates/footer.php';?>
</body>
</html>