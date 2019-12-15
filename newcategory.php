<?php

# add new category
session_start();

require_once ('session.class.php');
require_once ('db.class.php');
require_once ('common/funlib.php');

# check if the user is admin else redirect to main

if ($_SESSION['user'] !== 'admin'){
    redirectAndExit('index.php');
}

$db = new DB();

# add new category
if (isset($_POST['category']))
{
    addNewCategory($db);
    $categoryName = $_POST['category'];
    $fileName = $categoryName . '.php';
    $toFile = 'categories/' . $fileName;
    $newFile = fopen($toFile, "a");
    $contents = '<?php $categoryName = "' . $categoryName . '";';
    $contents .= file_get_contents('templates/templateCategory.php');
    fwrite($newFile, $contents);
    fclose($newFile);
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Add category</title>
    <?php require_once('templates/head.php');?>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>
<body>
<div class="wrapper">
<?php require_once('templates/top-menu.php');?>

<h1>Next categories available now:</h1>

<div class="admin"><?= availableCategories($db); ?></div>

<h1>To add category submit it's name below</h1>
<form action="newcategory.php" method="POST" class="loginForm">
<input type="text" id="category" name="category" placeholder="category"><br>
<button type="submit" name="submit" class="btn btn-primary" value="Submit">Submit</button>
</form>

    <div class="push"></div>
</div>
<?php require_once('templates/footer.php');?>
</body>
</html>
