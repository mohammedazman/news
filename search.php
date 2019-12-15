<?php

# search thought the entire site

session_start();
require_once 'db.class.php';
require_once 'common/funlib.php';
require_once 'session.class.php';

$db = new DB();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <?php require_once 'templates/head.php';?>
    <!-- SEARCH SCRIPT -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>
<body>
<div class="wrapper">
<?php require_once 'templates/top-menu.php';?>

<h1>Search results:</h1>
<?php paginationSearch(5, $db); ?>



<?php require_once 'templates/footer.php';?>
</body>
</html>