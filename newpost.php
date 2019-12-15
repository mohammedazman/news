<?php

# creating new category

session_start();
require_once 'session.class.php';
require_once 'db.class.php';
require_once 'common/funlib.php';

$db = new DB();

# the upload var is used to check if there will a file to upload
$upload = 0;

# if the user is not admin redirect to main
if ($_SESSION['user'] !== 'admin') {
    redirectAndExit('index.php');
}


$postErr=1;
$titleErr='';
$categoryErr='';
$textErr='';
$uploadErr='';

# check if all required fields of the form are filled

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $postErr=0;
  $name = nameNewPost();


    if (empty($_POST['category']))
    {
        $categoryErr = "Please input category";
        $postErr = 1;
    }
    if (empty($_POST['title']))
    {
        $titleErr = "Please input title";
        $postErr = 1;
    }
    if (empty($_POST['text']))
    {
        $textErr = "Please input text";
        $postErr = 1;
    }

# if everything is OK create new file with new post

    if ($postErr == 0)
    {
        $toFile = 'posts/' . $name;
        $newFile = fopen($toFile, "a");
        copy('templates/templatePost.php', $toFile);
        fclose($newFile);
    }
}

# upload picture and add it to post

if ($_FILES)
{
    $upload = 1;
    $file = $_FILES["fileToUpload"]["name"];
    $file_tmp = $_FILES["fileToUpload"]["tmp_name"];
    $file_size = $_FILES["fileToUpload"]["size"];
    $target_dir = "pictures/";
    $filename = uploadFile($target_dir, $file, $file_tmp, $file_size);
}

# add new record to the database

if ($postErr === 0)
{
    switch ($upload)
    {
        case "1":
                $sql = $db->query("INSERT INTO `news`( `title`, `text`, `picture`, `category`, `tags`, `analitics`, `url`)
                           VALUES ('{$_POST['title']}','{$_POST['text']}','{$filename}','{$_POST['category']}', '{$_POST['tags']}', '{$_POST['analytics']}', '{$name}')");
                redirectAndExit($toFile);
                break;

        case "0":
                $sql = $db->query("INSERT INTO `news`( `title`, `text`, `category`, `tags`, `analitics`, `url`)
                           VALUES ('{$_POST['title']}','{$_POST['text']}','{$_POST['category']}', '{$_POST['tags']}', '{$_POST['analytics']}', '{$name}')");
                redirectAndExit($toFile);
                break;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>ADD NEW POST</title>
    <?php require_once 'templates/head.php';?>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>
<body>
<div class="wrapper">
<?php require_once 'templates/top-menu.php';?>

    <h1>Add new category</h1>
    <form id='newpost' method='post' action='newpost.php' enctype='multipart/form-data'>
        <div class="newArt">
        <h4>Choose category * <?php echo $categoryErr;?> </h4>
        <?php echo checkboxCategories($db); ?><br>
        <h4> Title * <?php echo $titleErr;?></h4>
        <input type="text" name="title" placeholder="Title"><br><br>
        <h4>Text: * <?php echo $textErr;?></h4>
        <textarea form ="newpost" name="text" id="textarea" cols="45" rows="10"></textarea><br><br>
        <h4>If the type of the post is analytics, check the box:</h4>
        <p><input type="checkbox" name="analytics" value="yes"> The type of the post is "analytics"?</p>
        <h4>Tags:</h4>
        <input type="text" name="tags" placeholder="Tags"><br>
        <p></p>
        <p>Please input tags using the next scheme:</p>
        <p>1. Separate tags with one whitespace;</p>
        <p>2. Each tag should start with '#' sign;</p>
        <p>3. There should be no whitespaces between tags and '#' sign;</p>
        <p>3. There should be no whitespaces inside tag;</p>
        <h4>Upload picture</h4>
        <input type="file" name="fileToUpload"><?php echo $uploadErr; ?><br>
        <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
        </div>
    </form>

    <div class="push"></div>
</div>
<?php require_once 'templates/footer.php';?>
</body>
</html>
