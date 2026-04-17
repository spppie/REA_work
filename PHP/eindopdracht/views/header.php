<header class="border-bottom">
    <div class="border-right outside-padding perma-home-button">
        <h1><a href="home" id="perma-home-link">blog name</a></h1>
    </div>
    <?php 
        if (isset($_POST['create-comment'])) {
            echo $postManager->createComment();
        }
        if (isset($_POST['create-post'])) {
            echo $postManager->createPost();
        }
        if (isset($_POST['edit-post-submit'])) {
            echo $postManager->editPost($postArray);
        }
    ?>
    <?php if($_SESSION['inval']) { ?>
        <p>invalid username or password:</p>
    <?php } $_SESSION['inval'] = false; ?>
    <div class="border-left outside-padding">
        <?php if(!$_SESSION['logged']) { 
            include_once('views/loginForm.php');
        } else { ?>
            <div id="loggedin">
                <p><?= $_SESSION['user'] ?></p>
                <p><a href="users/<?=$_SESSION['user']?>">view account</a></p>
                <form id="logout-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
                    <input type="submit" value="log out" id="logout" name="logout">
                </form>
            </div>
        <?php } ?>
    </div>
</header>