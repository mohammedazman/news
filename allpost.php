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

$posts = $db->query('SELECT * FROM `news`');



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

  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>TEXT</th>
        <th>PICTURE</th>
        <th>CATEGORY</th>
        <th>TAGS</th>
        <th>URL</th>
        <th>DATE</th>
          <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($posts as $post) : ?>
                    <tr>
                  <td><?php echo $post['title']; ?></td>
          <td><?php echo $post['text']; ?></td>
          <td><?php echo $post['picture']; ?></td>
          <td><?php echo $post['category']; ?></td>
          <td><?php echo $post['tags']; ?></td>
          <td><?php echo $post['url']; ?></td>
          <td><?php echo $post['date']; ?></td>
          <td><a href="edit.php?post_id=<?php echo $post['id']; ?>">Edit</a> / <a href="delete.php?post_id=<?php echo $post['id']; ?>">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
    </tbody>
  </table>


<?php require_once('templates/footer.php');?>
</body>
</html>
