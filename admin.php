<?php

# admin panel

session_start();
require_once 'session.class.php';
require_once 'db.class.php';
require_once 'common/funlib.php';

$db = new DB();

# if the user is not admin redirect to main
if ($_SESSION['user'] == 'admin'){
    $isAdmin = 1;

} else {
    redirectAndExit('index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <?php require_once 'templates/head.php'; ?>
    <!-- SEARCH SCRIPT -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>
<body>
<div class="wrapper">
<?php require_once 'templates/top-menu.php';?>
    <h1>Select the action</h1>
<p></p>
    <div class="admin">
<p>To add new category go <a href="newcategory.php">here</a></p>
<p>To add new post go here <a href="newpost.php">here</a></p>
    </div>
    <div class="push"></div>
</div>
<?php require_once 'templates/footer.php';?>
</body>
</html>
