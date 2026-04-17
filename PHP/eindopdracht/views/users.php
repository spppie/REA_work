<div class="outside-padding include-flex" id="user-list-div">
    <?php 
        if(isset($_GET['username'])) {
            echo $userManager->getAccount('user');
            echo $postManager->showPostsProfile($postArray);
        } else { 
            foreach($userArray as $user) {
                echo 
                    '<section id="profile-section" class="user-list-profile-section outside-padding">
                        <img src="'.$user['img'].'" alt="profile photo of '.$user['username'].'">
                        <div>
                            <p>username: '.$user['username'].'</p>
                            <p>email: '.$user['email'].'</p>
                            <p>view account: <a href="users/'.$user['username'].'">here</a></p>
                        </div>
                    </section>';
            }
        } 
    ?>
</div>
