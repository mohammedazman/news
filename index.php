<?php

session_start();
require_once 'session.class.php';
require_once 'db.class.php';
require_once 'common/funlib.php';

# connect to the db
$db = new DB();

# select the categories from db and show them + 5 the most current news

$categories = $db->query('SELECT * FROM `categories`');
$html = '';
foreach ($categories as $key=>$value)
{
    $category = $value["cat_title"];
    $category_id = $value["id"];
    $html .= '<a  href="categories/' . $category . '.' . "php" . '"><h2 class="cat_header alert alert-info">' . ucfirst($category) . '</h2></a><br>';
    $posts = $db->query("SELECT * FROM `news` WHERE `category`= '{$category_id}' LIMIT 5");
    $html .= '<div class="articles">' . showTitle($posts) . '</div>';
}

#select pictures for the carousel

$pictures = $db->query("SELECT `picture` FROM `news` WHERE NOT `picture` = '' ORDER BY  `news`.`date` DESC LIMIT 3;");
$picsArr = Array();
$picsName = Array();
$titlesArr = Array();
foreach ($pictures as $number => $arr) {
    $pictureName = $arr['picture'];
    $imgPath = "http://localhost/php/newweek/pictures/" . $pictureName;
    array_push($picsArr, $imgPath);
    array_push($picsName, $pictureName);
    $title = $db->query("SELECT `title`  FROM `news` WHERE `picture`= '{$pictureName}'");
    array_push($titlesArr, $title);
}

#get top 5 commentators from db
$topCommentators = topFiveCommentators($db);

#get top 5 commented news from db
$topNews = getTopThreeCommentedNews($db);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>NEWEEK</title>
        <?php require_once 'templates/head.php';?>
        <!-- SEARCH SCRIPT -->
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script src="templates/dynamic.js"></script>
    </head>

    <body>
    <?php require_once 'templates/top-menu.php';?>
    <?php require_once 'templates/carousel.php';?>
    <div class="wrapper">
    <p><?=isset($_GET['msg']) ? $_GET['msg'] : '';?></p>

    <h4>TOP COMMENTATORS</h4>
    <p><?= formatCommentators($topCommentators) ?></p>
    <h4>TOP NEWS</h4>
    <p><?= formatTopThree($topNews) ?></p>

    <?php echo $html; ?>

    <div class="push"></div>
    </div>
    <div class="clear">

    </div>
    <?php require_once 'templates/footer.php';?>
    </body>
</html>
