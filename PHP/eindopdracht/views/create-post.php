<?php
    if (isset($_GET['postid'])) {
        foreach($postArray as $post) {
            if ((string)$post['id'] === $_GET['postid']) {
                $postTitle = $post['title'];
                $postContent = $post['content'];
            }
        }
    }
?>
<section class="outside-padding include-flex">
    <div class="create-post-div outside-padding">
        <?php if(!$_SESSION['logged']) { ?>
            <p>Please log in to create posts</p>
        <?php } else if(isset($_GET['postid'])) { ?>
            <h2>Edit your post:</h2>
            <form id="post-edit-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
                <input type="submit" value="edit post" id="edit-post-submit" name="edit-post-submit">    
                <label for="post-edit-title">Title:</label>
                <input type="text" name="post-edit-title" id="post-edit-title" value="<?= $postTitle ?>">
                <input type="hidden" name="postid" id="postid" value="<?= $_GET['postid'] ?>">
                <label for="detail">content:</label>
                <textarea id="post-text-area" name="post-text-area"><?= $postContent ?></textarea>
                <script>
                        ClassicEditor.create(document.querySelector('#post-text-area'),{
                            toolbar: [ 'undo', 'redo', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 'bulletedList', 'numberedList' ]
                        }).catch(error => console.error(error));
                </script>
            </form>
        <?php } else { ?>
            <h2>Write a new post:</h2>
            <form id="post-create-form" method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
                <input type="submit" value="create post" id="create-post" name="create-post">    
                <label for="post-create-title">Title:</label>
                <input type="text" name="post-create-title" id="post-create-title">
                <label for="detail">content:</label>
                <textarea id="post-text-area" name="post-text-area"></textarea>
                <script>
                        ClassicEditor.create(document.querySelector('#post-text-area'),{
                            toolbar: [ 'undo', 'redo', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 'bulletedList', 'numberedList' ]
                        }).catch(error => console.error(error));
                </script>
            </form>
        <?php } ?>
    </div>
</section>