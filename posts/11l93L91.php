<?php

session_start();

#resolving path to the root folder

$folderPath = (__DIR__);
$rootUrl = substr($folderPath, 0, strrpos($folderPath, '/') + 1);
define('ROOT',  $rootUrl);

require_once ROOT . 'db.class.php';
require_once ROOT . 'common/funlib.php';
require_once ROOT . 'session.class.php';

$db = new DB();

$sessionId = session_id(); # sessionId is needed for counting users on current page and views
$address = getFileName(); # the name of the page


# ask the database the post details

$post = $db->query("SELECT `title`, `text`, `id`, `date`, `picture`, `tags`, `category`, `analitics`
                    FROM news
                    WHERE `url`='{$address}'");
$title = $post[0]['title'];
$postId = $post[0]['id'];
$category = $post[0]['category'];
$analytics =  $post[0]['analitics'];

# add information about the view of current page to users counter

if ($postId) {
    $check = $db->query("SELECT `id`
                         FROM `count_visit`
                         WHERE `session_id` = '{$sessionId}'");
    if (!$check) {
        $insertId = $db->query("INSERT INTO `count_visit`(`session_id`, `post_id`)
                                VALUES ('{$sessionId}', '{$postId}')");
    }
    $viewersNow = $db->query("SELECT COUNT(*)
                              FROM `count_visit`
                              WHERE (`time_stamp` BETWEEN DATE_SUB(NOW(), INTERVAL 3 MINUTE) AND NOW())
                              AND `post_id` = '{$postId}'");

    $viewersTotal = $db->query("SELECT COUNT(*)
                                FROM `count_visit`
                                WHERE  `post_id` = '{$postId}'");
}

# get comments for the post

$commentsArray = getCommentsForPost($db, $postId);
$size = count($commentsArray);
$commentsCounter = 0; # the initial point for while loop showing the comments

# add the comment to post
if ($_POST) {
    if ($_POST['comment-text']) {
        switch ($_GET['action']) {
            case 'add-comment':
                $commentData = array(
                    'text' => $_POST['comment-text']
                );
                addComment($db, $commentData, $postId);
                redirectAndExit($address);
                break;
            case 'delete-comment':
                # TODO: add the possibility for admin to delete comments
                break;
        }
    } else {
        $commentData = array(
            'text' => '',
        );
    }
}

# add the like to the comment

if ($_GET) {
    if ($_GET['like']) {
        $commentId = $_GET['like'];
        $userId = $_GET['userid'];
        $check = $db->query("SELECT `likes` FROM `likes`
                             WHERE `commentId`='{$commentId}'
                             AND `userId` = '{$userId}'
                             AND `likes` = 1");
        if (!$check) {
            $request = $db->query("INSERT INTO `likes`(`commentId`, `userId`, `likes`)
                                   VALUES ('{$commentId}', '{$userId}', 1)");
            redirectAndExit($address);
        }
    }

# add the dislike to the comment

    elseif ($_GET['dislike']) {
        $commentId = $_GET['dislike'];
        $userId = $_GET['userid'];
        $check = $db->query("SELECT `likes` FROM `likes`
                             WHERE `commentId`='{$commentId}'
                             AND `userId` = '{$userId}'
                             AND `dislikes` = 1");
        if (!$check) {
            $request = $db->query("INSERT INTO `likes`(`commentId`, `userId`, `dislikes`)
                                   VALUES ('{$commentId}', '{$userId}', 1)");
            redirectAndExit($address);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title;?></title>
    <?php require_once ROOT. "templates/head.php"; ?>
    <!-- SEARCH SCRIPT -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="<?php echo ROOT ?>templates/dynamic.js"></script>
</head>

<body>
<div class="wrapper">
<?php require_once ROOT . 'templates/top-menu.php';?>

<h1><?php echo $title;?></h1>

<p><?php echo isAnalytics($db, $postId, $post)?></p>

<p class="note"><?= "VIEWERS NOW: " . $viewersNow[0]['COUNT(*)'] ?></p>
<p class="note"><?= "VIEWERS TOTAL: " . $viewersTotal[0]['COUNT(*)'] ?></p>

<h1>COMMENTS</h1>
    <?php if ($size === 0): ?>
    <p class="postText">There is no comments yet. You could be first to add a comment</p>
    <?php else: ?>
    <?php while ($commentsCounter<$size):?>
        <h5><b>Comment:</b></h5>
        <p class="comment"><?= $commentsArray[$commentsCounter]['text']; ?><p>
        <h5><b>Date:</b> <?= $commentsArray[$commentsCounter]['date']; ?></h5>
        <h5><b>Comment by: <?= $commentsArray[$commentsCounter]['username']; ?>, <?= $commentsArray[$commentsCounter]['email']; ?></b></h5>

        <?php if(isLoggedIn()): ?>

        <form action="<?php echo $address; ?>?like=<?php echo $commentsArray[$commentsCounter]['commentid'] ?>&amp;userid=<?php echo $_SESSION['id'] ?>" method="post" class="block">
             <button type="submit" value="like"  class="glyphicon glyphicon-heart"></button> I like the comment
        </form>

        <form action="<?php echo $address; ?>?dislike=<?php echo $commentsArray[$commentsCounter]['commentid'] ?>&amp;userid=<?php echo $_SESSION['id'] ?>" method="post" class="block">
              <button type="submit" value="dislike" class="glyphicon glyphicon-minus"></button> I do not like the comment
        </form>

        <?php endif; ?>

        <?php $commentsCounter++; ?>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php if(isLoggedIn()): ?>
        <h1>LEAVE COMMENT</h1>
        <form action="<?php echo $address; ?>?subject=<?php echo $postId?>&amp;action=add-comment" method="post" class="newComment">
        <textarea id="comment-text" name="comment-text" rows="8" cols="70"><?php echo htmlEscapeFull($commentData['text']) ?></textarea>
        <div>
            <button type="submit" value="Submit comment" class="btn btn-success">Add comment</button>
        </div>
        </form>

<?php else: ?>
    <p class="postText">Please <a href="<?= ROOT ?>login.php">login</a> to leave comments</p>
<?php endif; ?>
    <div class="push"></div>
</div>
<?php require_once ROOT. 'templates/footer.php';?>
</body>
</html>