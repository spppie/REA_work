<?php
    if (isset($_POST['sort-type'])) {
        $_SESSION['sort-by'] = $_POST['sort-type'];
    }
    if (!isset($_SESSION['sort-amount'])) {
        $_SESSION['sort-amount'] = 10;
    }
    if (isset($_POST['sort-amount'])) {
        $_SESSION['sort-amount'] = $_POST['sort-amount'];
    }
    if (isset($_POST['delete-comment'])) {
        $postManager->deleteComment();
    }
    if (isset($_POST['delete-post'])) {
        $postManager->deletePost();
    }
?>
<section class="border-bottom outside-padding include-flex" id="home-page-main-flex">
    <form id="sort-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
        <input type="submit" value="sort" id="sort" name="sort">
        <label for="sort-type">based on:</label>
        <select name="sort-type" id="sort-type">
            <option value="date-desc" <?php if(isset($_SESSION['sort-by']) && $_SESSION['sort-by'] === 'date-desc') {echo 'selected';} ?>>date descending</option>
            <option value="date-asc" <?php if(isset($_SESSION['sort-by']) && $_SESSION['sort-by'] === 'date-asc') {echo 'selected';} ?>>date ascending</option>
            <option value="author-A-Z" <?php if(isset($_SESSION['sort-by']) && $_SESSION['sort-by'] === 'author-A-Z') {echo 'selected';} ?>>author A-Z</option>
            <option value="author-Z-A" <?php if(isset($_SESSION['sort-by']) && $_SESSION['sort-by'] === 'author-Z-A') {echo 'selected';} ?>>author Z-A</option>
        </select>
        <label for="sort-amount">Amount:</label>
        <select name="sort-amount" id="sort-amount">
            <option value="10" <?php if(isset($_SESSION['sort-amount']) && $_SESSION['sort-amount'] === '10') {echo 'selected';} ?>>10</option>
            <option value="20" <?php if(isset($_SESSION['sort-amount']) && $_SESSION['sort-amount'] === '20') {echo 'selected';} ?>>20</option>
            <option value="30" <?php if(isset($_SESSION['sort-amount']) && $_SESSION['sort-amount'] === '30') {echo 'selected';} ?>>30</option>
            <option value="all" <?php if(isset($_SESSION['sort-amount']) && $_SESSION['sort-amount'] === 'all') {echo 'selected';} ?>>all</option>
        </select>
    </form>
    <form id="search-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
        <input type="submit" value="search" id="search" name="search">
        <input type="text" name="query-string" id="query-string" required <?php if(isset($_POST['query-string'])) {echo 'value="'.$_POST['query-string'].'"';} ?>>
        <label for="search-type">based on:</label>
        <select name="search-type" id="search-type">
            <option value="author" <?php if(isset($_POST['search-type']) && $_POST['search-type'] === 'author') {echo 'selected';} ?>>author</option>
            <option value="title" <?php if(isset($_POST['search-type']) && $_POST['search-type'] === 'title') {echo 'selected';} ?>>title</option>
            <option value="content" <?php if(isset($_POST['search-type']) && $_POST['search-type'] === 'content') {echo 'selected';} ?>>content</option>
        </select>
    </form>
</section>
<div id="post-list-div">
    <?php 
        if (isset($_GET['postid'])) {
            echo $postManager->displaySpecPost($postArray);
        } else if (isset($_POST['search'])) {
            echo $postManager->postSearch($postArray);
        } else {
            echo $postManager->displayAllPostInArray($postArray);
        }
    ?>
</div>