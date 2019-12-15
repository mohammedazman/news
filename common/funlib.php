<?php

# check the presence of image linked to post

function imageFinder($picture)
{
    if ($picture != '')
    {
        $imageLink = '<p class="image"><img src="http://localhost/php/newweek/pictures/' . $picture . '" width="600" height="400"></p>';
        return $imageLink;
    }
}

# shows individual posts on page

function sepPagePosts($arr)
{
    foreach ($arr as $key=>$value)
    {
        $post_text = $value["text"];
        $post_date = $value["date"];
        $post_image = $value["picture"];
        $tags = $value["tags"];
        echo '<p class="articles">' . $post_date . '<p>';
        echo imageFinder($post_image);
        echo '<p class="postText">' . $post_text . '</p>';
        echo tags($tags);
    }
}

# shows individual post title

function showTitle($arr)
{
    $html = '';
    foreach ($arr as $key => $value) {
        $html .=  '<div class="news_singl">

        <h2>'.$value["title"].'</h2>
        <img src="pictures/'.$value["picture"].'">
        <p>'.$value["text"].'</p><br><br><br><br><br>
        <br>
        <a href="posts/'. $value['url'] . '" >تفاصيل...</a><br><hr>

        </div>';
    }
    return $html;
}

# link to separate posts

function separatePost($arr)
{
    $html = '';
    foreach ($arr as $key => $value) {
        $html .= '<form action="http://localhost/php/newweek/posts/' . $value['url']. '" method="get" class="searchRes">';
        $html .= '<h3>'  . ucfirst($value['title']) . '</h3>';
        $html .= '<button name="subject" type="submit" class="btn btn-primary" value="' . $value['id'] . '">Read More</button></form>';
    }
    return $html;
}

# count amount of pages to paginate

function pagesCount($totalNumPages, $perPage=5)
{
    $pagesToPag = ceil($totalNumPages/$perPage);
    return $pagesToPag;
}

# pagination

function pagination($db, $limit, $category)
{

    $count = $db->query("SELECT COUNT(`id`) FROM news WHERE  `category`= '{$category}'");
    $id = 0;
    $start = 0;

    if(isset($_GET['id']))
    {
        $id=$_GET['id'];
        $start=($id-1)*$limit;
    }

    $pagesNumber = pagesCount(array_values($count[0])[0], $limit);

    $pagQuery = "SELECT `title`, `id`, `url` FROM news WHERE `category` = '{$category}' LIMIT " . $start . "," . $limit;
    $diapason = $db->query("$pagQuery");


    echo separatePost($diapason);

    if ($id > 1)
    {
        $previousPage = $id - 1;
    }
    else
    {
        $previousPage = 1;
    }

    # <div class="push"></div></div> are added to stick pagination to the end of the opening tags are in the head of the page
    echo "<div class=\"push\"></div>
          </div>
          <nav aria-label= 'Page navigation'>
          <ul class= 'pagination'>
          <li>
          <a href='?id=" . $previousPage . "' aria-label= 'Previous'>
          <span aria-hidden= 'true'>&laquo;</span>
          </a>
          </li>";

    for($i=1;$i<=$pagesNumber;$i++)
    {
        echo "<li class='page-item'><a a class='page-link' href='?id=".$i."'>".$i."</a></li>";
    }

    if ($id < $pagesNumber)
    {
        $nextPage = $id + 1;
    }
    else
    {
        $nextPage = $id;
    }

    echo "  <li>
            <a href='?id=" . $nextPage . "' aria-label= 'Previous'>
            <span aria-hidden='true'>&raquo;</span>
            </a>
            </li>
            </ul>
            </nav>";
}

#returns tags for each post

function tags($subject){
    $html = '';
    $tags = preg_split('/\s/', $subject);
    foreach ($tags as $key=>$value){
        $html .= "<a href=\"#\" class='tags'>$value</a>";
    }
    return $html;
}

#pagination search results

function paginationSearch($limit, $db)
{
    $id = 0;
    $start = 0;


    if(isset($_GET['id']))
    {
        $id=$_GET['id'];
        $start=($id-1)*$limit;
        $aim = $_GET['aim'];
        $search = '%' . $aim . '%';
    }

    if($_POST['search'] != '')
    {
        $aim = $_POST['search'];
        $search = '%' . $aim . '%';

    }

    if ($id == 0 AND is_null($_POST['search']))
    {
        echo  "<p>Please enter a search query</p>";
    }

    if(isset($_GET['id']))
    {
        $id=$_GET['id'];
        $start=($id-1)*$limit;
    }

    $searchResultsTotal = $db->query("SELECT `title`, `text` FROM news WHERE `title` LIKE '{$search}' OR `text` LIKE '{$search}'");
    $count = count($searchResultsTotal);
    $pagesNumber = pagesCount($count, $limit);


        if (isset($search))
        {
            $searchQuery = "SELECT `title`, `text`, `id`, `url` FROM news WHERE `title` LIKE '{$search}' OR `text` LIKE '{$search}'  LIMIT " . $start . "," . $limit;
            $searchResults = $db->query($searchQuery);
        }

        echo separatePost($searchResults);

        if ($id > 1)
        {
            $previousPage = $id - 1;
        }
        else
        {
            $previousPage = 1;
        }

    # <div class="push"></div></div> are added to stick pagination to the end of the opening tags are in the head of the page

    echo "<div class=\"push\"></div></div>
    <nav aria-label= 'Page navigation'>
    <ul class='pagination'>
    <li class='page-item'>
    <a href='?id=" . $previousPage . "&aim=" . $aim . "' aria-label= 'Previous' class='page-link'>
    <span aria-hidden= 'true'>&laquo;</span>
    </a></li>";

        for($i=1;$i<=$pagesNumber;$i++)
        {
            echo "<li class='page-item'><a a class='page-link' href='?id=".$i."&aim=".$aim."'>".$i."</a></li>";
        }

        if ($id < $pagesNumber)
        {
            $nextPage = $id + 1;
        }
        else
        {
            $nextPage = $id;
        }

    echo "  <li class='page-item'>
    <a href='?id=" . $nextPage .  "&aim=" . $aim . "' aria-label= 'Previous'  class='page-link'>
    <span aria-hidden='true'>&raquo;</span>
    </a>
    </li>
    </ul>
    </nav>";

}

# check if the user is logged in

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

# check if the post is from analytic category or type

function isAnalytics($db, $id, $arr)
{

    $sql = $db->query("SELECT `category`, `analitics` FROM `news` WHERE `id` = '{$id}'");
    $category = $sql[0]['category'];
    $analytics = $sql[0]['analitics'];
    $logged = isLoggedIn();

    switch ($logged) {
        case(0):
            if ($category == 3) {
                sepPagePostsLimited($arr);
            } elseif ($analytics === 'yes') {
                sepPagePostsLimited($arr);
            } else {
                sepPagePosts($arr);
            }
            break;
        case(1):
            sepPagePosts($arr);
            break;
    }
}


# limit the amount of sentences for unregistered/unlogged in users

function viewLimit($string)
{
    if (strlen($string) > 200) {

        // truncate string
        $stringCut = substr($string, 0, 400);

        // make sure it ends in a word so assassinate doesn't become ass...
        $outString = substr($stringCut, 0, strrpos($stringCut, ' '));
    }
    return $outString;
}

# posts view for the unregistered/unlogged users

function sepPagePostsLimited($arr)
{

    foreach ($arr as $key=>$value)
    {
        $post_text = viewLimit($value["text"]);
        $post_date = $value["date"];
        $post_image = $value["picture"];
        $tags = $value["tags"];
        echo '<p class="articles">' . $post_date . '</p>';
        echo imageFinder($post_image);
        echo '<p class="postText">' . $post_text . '...</p>' . '<p class="postText"><b>To view full text <a href="http://localhost/php/newweek/register.php">register</a> or <a href="http://localhost/php/newweek/login.php">login</a></b></p>';
        echo tags($tags);
    }
}


# Comments for the post (array)

function getCommentsForPost($db, $postId)
{
    $sql = $db->query(" SELECT  `comments`.`text_com` ,  `comments`.`date_com` ,  `comments`.`user_id` ,  `comments`.`id` ,  `users`.`username` ,  `users`.`email`,
      SUM(  `likes`.`likes` ) AS  `sumL` , SUM(  `likes`.`dislikes` ) AS  `sumD`, (SUM(  `likes`.`likes` )- SUM(  `likes`.`dislikes` )) AS `all`
                       FROM  `comments`
                       INNER JOIN  `users` ON  `comments`.`user_id` =  `users`.`id`
			           LEFT JOIN `likes` ON `comments`.`id` = `likes`.`commentId`
                       WHERE `comments`.`news_id` ='{$postId}'
			           GROUP BY  `likes`.`commentId`, `comments`.`date_com`
			           ORDER BY `all` DESC ");
    $size = count($sql);
    $arr = Array();
    $arr2 = Array();
    $i = 0;
    while ($i < $size) {
        $arr['username'] = $sql[$i]['username'];
        $arr['email'] = $sql[$i]['email'];
        $arr['date'] = $sql[$i]['date_com'];
        $arr['text'] = $sql[$i]['text_com'];
        $arr['commentid'] = $sql[$i]['id'];
        $arr2[$i] = $arr;
        $i++;
    }
    return $arr2;
}


# Escape HTML for comments

function htmlEscapeFull($html)
{
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

# Redirects to set url

function redirectAndExit($script)
{
    $relativeUrl = $_SERVER['PHP_SELF'];
    $urlFolder = substr($relativeUrl, 0, strrpos($relativeUrl, '/') + 1);
    $host = $_SERVER['HTTP_HOST'];
    $fullUrl = 'http://' . $host . $urlFolder . $script;
    header('Location: ' . $fullUrl);
    exit();
}

# add comments to post

function addComment($db, $commentData, $postId) {
    if ($commentData){
        $text = htmlEscapeFull($commentData['text']);
        $userId = $_SESSION['id'];
        $db->query("INSERT INTO `comments`(`text_com`, `user_id`, `news_id`) VALUES ('{$text}', '{$userId}', '{$postId}')");
    }
    else {
        echo "Comment cannot be empty";
    }
}

# Get's the top 5 users that left comments

function topFiveCommentators($db)
{
    $arr = $db->query("SELECT  `comments`.`user_id` , COUNT(  `comments`.`id` ) AS `all`,  `users`.`username`
                       FROM  `comments`
                       LEFT JOIN  `users` ON  `users`.`id` =  `comments`.`user_id`
                       GROUP BY  `user_id`
                       ORDER BY `all` DESC
                       LIMIT 5");
    return $arr;
}

# format top 5 commentators

function formatCommentators($arr)
{
    $size = count($arr);
    $i = 0;
    $html = "";
    while ($i < $size)
    {
        $num = $i + 1;
        $html .= '<p>' . $num . ". <b>" . $arr[$i]['username'] . "</b> left " . $arr[$i]['all'] . " comments" . '</p>';
        $i++;
    }
    return $html;
}

# get's the top 3 themes with the most comments

function getTopThreeCommentedNews($db)
{
    $arr = $db->query("SELECT  `comments`.`news_id` , COUNT(  `comments`.`id` ) AS  `all` ,  `news`.`title`, `news`.`url`
                       FROM  `comments`
                       LEFT JOIN  `news` ON  `news`.`id` =  `comments`.`news_id`
                       GROUP BY  `news_id`
                       ORDER BY  `all` DESC
                       LIMIT 3");
    return $arr;
}

# format top 3 news

function formatTopThree($arr)
{
    $size = count($arr);
    $i = 0;
    $html = "";
    while ($i < $size)
    {
        $num = $i + 1;
        $html .= '<p>' . $num . ". <b><a href='posts/" . $arr[$i]['url'] . "'>" . $arr[$i]['title'] . "</b></a> with " . $arr[$i]['all'] . " comments" . '</p>';
        $i++;
    }
    return $html;
}

# adding new category

function addNewCategory($db)
{
    $category = $_POST['category'];
    $sql = $db->query("INSERT INTO `categories`(`cat_title`) VALUES ('{$category}')");
}

# existing categories

function availableCategories($db)
{
    $categories = $db->query('SELECT `cat_title` FROM `categories`');
    $size = count($categories);
    $i = 0;
    $html = '';
    while ($i < $size)
    {
        $number = $i + 1;
        $html .= "<p>" . $number . ". <a href='categories/" . $categories[$i]['cat_title'] . '.' . "php" . "'>" . $categories[$i]['cat_title'] . '</a>'. '</p>';
        $i++;
    }
    return $html;
}

# existing categories checkbox

function checkboxCategories($db)
{
    $categories = $db->query('SELECT `id`, `cat_title` FROM `categories`');
    $size = count($categories);
    $i = 0;
    $html = '';
    while ($i < $size)
    {
        $html .= '<p><input type="checkbox" name="category" value="' . $categories[$i]["id"] . '"> ' . $categories[$i]['cat_title'] . '</p>';
        $i++;
    }
    return $html;
}

# create name for new article file

function nameNewPost()
{
    $name = '';
    $titleSplit = str_split($_POST['title'], 1);
    $textSplit = str_split($_POST['text'], 1);
    $name .= $_POST['category'];
    $name .= $titleSplit[0];
    $name .= rand(0, 100);
    $name .= $textSplit[0];
    $name .= rand(0, 100);
    $name .= '.php';
    return $name;
}

# get file name

function getFileName()
{
    $relativeUrl = $_SERVER['PHP_SELF'];
    $urlName = substr($relativeUrl, strrpos($relativeUrl, '/') + 1, $finish = strrpos($relativeUrl, '.') + 3);
    return $urlName;
}

# upload pictures on server

function uploadFile($target_dir, $name, $file_tmp, $file_size)

{
    $file = strtolower(preg_replace('/[^a-zA-Z0-9.\']/', "", $name));;
    $target_file = $target_dir . basename($file);
    $upload_ok = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    if ($_FILES) {
        $check = getimagesize($file_tmp);
        if($check !== false) {
            $upload_ok = 1;
        } else {
            $uploadErr .= "File is not an image.";
            $upload_ok = 0;
        }
    }
// Check if image file is already uploaded
    if (file_exists($target_file)) {
        $uploadErr .= "Sorry, file already exists.";
        $upload_ok = 0;
    }
// Check file size
    if ($file_size > 90000000) {
        $uploadErr .= "Sorry, your file is too large.";
        $upload_ok = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $uploadErr .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }
// Check if $upload_ok is set to 0 by an error
    if ($upload_ok == 0) {
        $uploadErr .= "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($file_tmp, $target_file)) {
            return $file;
        } else {
            $uploadErr .= "Sorry, there was an error uploading your file.";
        }
    }
}

# existing categories for top menu

function categoriesTop($db)
{
    $categories = $db->query('SELECT `cat_title` FROM `categories`');
    $size = count($categories);
    $namesArray = [];
    $i = 0;
    if (defined('ROOT')) {
        while ($i < $size) {
            $html = '<a href="' . ROOT . 'categories/' . $categories[$i]['cat_title'] . '.' . "php" . '">' . $categories[$i]['cat_title'] . '</a>';
            array_push($namesArray, $html);
            $i++;
        }
    }
    else {
        while ($i < $size) {
            $html = '<a href="categories/' . $categories[$i]['cat_title'] . '.' . "php" . '">' . $categories[$i]['cat_title'] . '</a>';
            array_push($namesArray, $html);
            $i++;
        }
    }
    return $namesArray;
}
