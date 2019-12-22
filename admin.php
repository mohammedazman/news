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

#get top 5 commentators from db
$topCommentators = topFiveCommentators($db);

#get top 5 commented news from db
$topNews = getTopThreeCommentedNews($db);
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
<h4>TOP COMMENTATORS</h4>
<p><?= formatCommentators($topCommentators) ?></p>
<h4>TOP NEWS</h4>
<p><?= formatTopThree($topNews) ?></p>

    <h1>Select the action</h1>
<p></p>
    <div class="admin">
<p>To add new category go <a href="newcategory.php">here</a></p>
<p>To add new post go here <a href="newpost.php">here</a></p>
<p>To show all posts go here <a href="allpost.php">here</a></p>
    </div>
    <div class="push"></div>
</div>
<?php require_once 'templates/footer.php';?>
</body>
</html>
