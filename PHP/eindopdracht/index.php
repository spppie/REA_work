<?php
    declare(strict_types=1);

use Jouw\Namespace\Hier\mailHelper;

    session_start();
    require_once('vendor/autoload.php');

    date_default_timezone_set("Europe/Amsterdam");

    $cur_date = date('Y-m-d');
    setcookie('current_date', $cur_date, time()+60*60*24*365, '/');
    // if (isset($_COOKIE['current_date'])) {
    //     $first_time = strtotime($cur_date);
    //     $second_time = strtotime($_COOKIE['current_date']);
    //     $time_elapsed = round(($first_time - $second_time)/60);
    // }

    // var dump test pre:
    // echo '<pre>';

    // echo uniqid();'

    $blacklist = array('fuck');

    // login class:
    $userManager = new userManager('xml/userdata.xml');

    // user loader class:
    $userLoader = new userLoader('xml/userdata.xml');
    $userArray = $userLoader->loadAllUsers();

    // page loader class:
    $pageLoader = new pageLoader();

    // post loader class:
    $postManager = new postManager('xml/postdata.xml', $blacklist);
    $postArray = $postManager->loadInAllPosts();
    
    // show all user data:
    // $userManager->showUserData('admin', 'root');

    // loads page:
    $include = $pageLoader->loadPage();

    // login call logic:
    if (isset($_POST['login'])) {
        $userManager->login($_POST['username'], $_POST['password']);
    }
    if (isset($_POST['logout'])) {
        $userManager->logout();
    }
?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="UTF-8">
        <title>blog</title>
        <meta name="description" content="blog website">
        <base href="http://localhost/REA/PHP-main/25-eindopdracht-php/root/">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="css/stylesheet.css">
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    </head>
    <body>
        <div id="container">
            <?php require_once('views/header.php'); ?>
            <main>
                <nav class="border-bottom">
                    <ul id="nav-ul">
                        <li <?= $_GET['page'] === 'home' ? 'id="nav-selected-box"><a id="nav-selected-text"' : '><a'?> href="home">home</a></li>
                        <?php if($_SESSION['logged']) {?>
                        <li <?= $_GET['page'] === 'create-post' ? 'id="nav-selected-box"><a id="nav-selected-text"' : '><a'?> href="create-post">create post</a></li>
                        <?php } ?>
                        <li <?= $_GET['page'] === 'users' ? 'id="nav-selected-box"><a id="nav-selected-text"' : '><a'?> href="users">users</a></li>
                        <?php if($_SESSION['logged'] && $_SESSION['user'] === 'admin') {?>
                        <li <?= $_GET['page'] === 'admin-page' ? 'id="nav-selected-box"><a id="nav-selected-text"' : '><a'?> href="admin-page">admin page</a></li>
                        <?php } ?>
                    </ul>
                </nav>
                <?php require_once($include); ?>
            </main>
            <footer>
                <p class="outside-padding">made by <a href="https://github.com/spppie" id="footer-link">Spoopie</a> 2026</p>
                <p class="outside-padding">last visit: <?php if(isset($_COOKIE['current_date'])) { echo $_COOKIE['current_date']; } else { echo "first visit, or last visit too long ago."; }?></p>
            </footer>
        </div>
    </body>
</html>