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

if (isset($_REQUEST['post_id']))
{
    $post_id = htmlentities($_REQUEST['post_id']);

    if (isset($_POST['delete']))
    {
        $stmt = $db->query("
            DELETE FROM news
            WHERE id = $post_id
        ");


        $_SESSION['status'] = 'Record deleted';
        $_SESSION['color'] = 'green';

        header('Location: allpost.php');
        return;
    }

    $posts   = $db->query("SELECT * FROM news WHERE id = $post_id ");






}



?>

<!DOCTYPE html>
<html>
<head>
    <title>DELETE posts</title>
    <?php require_once('templates/head.php');?>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="templates/dynamic.js"></script>
</head>
<body>
<div class="wrapper">
<?php require_once('templates/top-menu.php');?>
<div class="container " >



    <form method="post" class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-2">
              <p>
                  Confirm: Deleting <b> <?php foreach ($posts as $post) {
                    echo $post['title'];
                  }?>
                  </b>
              </p>
                <input class="btn btn-primary" type="submit" name="delete" value="Delete">
                <a class="btn btn-default" href="index.php">Cancel</a>
            </div>
        </div>
    </form>

</div>



<?php require_once('templates/footer.php');?>
</body>
</html>
