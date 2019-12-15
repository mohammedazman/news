<?php $categoryName = "Politics";
session_start();

#resolving path to the root folder

$folderPath = (__DIR__);
$rootUrl = substr($folderPath, 0, strrpos($folderPath, '/') + 1);
define('ROOT',  $rootUrl);

require_once(ROOT . 'db.class.php');
require_once(ROOT . 'common/funlib.php');

$db = new DB();
$categorySql = $db->query("SELECT `id`
                           FROM `categories`
                           WHERE `cat_title` = '{$categoryName}'");
$categoryId = $categorySql[0]['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $categoryName ?></title>
    <?php require_once ROOT. "templates/head.php"; ?>
    <!-- SEARCH SCRIPT -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="<?php echo ROOT ?>templates/dynamic.js"></script>
</head>
<body>
<div class="wrapper">
<?php require_once ROOT . 'templates/top-menu.php';?>

<h1><?= $categoryName ?></h1>
<?php pagination($db, 5, $categoryId); ?>


<?php require_once ROOT . 'templates/footer.php';?>
</body>
</html>
