<?php

$numCat = count(categoriesTop($db)); # the categories for the dropdown
$arr = categoriesTop($db);
$i = 0;
 ;
require_once $_SERVER['DOCUMENT_ROOT'].'/php/newweek/session.class.php';


?>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://localhost/php/newweek/index.php">NEWWEEK</a>
        </div>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="http://localhost/php/newweek/admin.php">Admin panel</a></li>
            </ul>
            <form action="http://localhost/php/newweek/search.php" class="searchf" role="search" id="searchform" method="POST">
                <div class="search-box col-md-4">
                    <input type="text" autocomplete="off" placeholder="Search" name="search" class="searchInput">
                    <div class="result"></div>
                    <button type="submit" name="submit" class="btn btn-primary searchInput" value="search">SEARCH</button>
                </div>
            </form>
                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php while($i < $numCat):  ?>
                            <li><?php echo $arr[$i]; ?></li>
                            <?php $i++;?>
                            <?php endwhile; ?>
                        </ul>
                    </li>

                    <?php if(isLoggedIn()): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Wello <?= Session::get('user'); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="http://localhost/php/newweek/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Login<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="http://localhost/php/newweek/login.php">Login</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="http://localhost/php/newweek/register.php">Register</a></li>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
