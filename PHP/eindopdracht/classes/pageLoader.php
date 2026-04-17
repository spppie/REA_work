<?php
    class pageLoader {
        public function loadPage() {
            if (!isset($_GET['page'])) {
                if (isset($_SESSION['page'])) {
                    $_GET['page'] = $_SESSION['page'];
                } else {
                    $_GET['page'] = 'home';
                    $_SESSION['page'] = $_GET['page'];
                }
            }
            switch($_GET['page']) {
                case 'home':
                    $include = 'views/home.php';
                    break;
                case 'users':
                    $include = 'views/users.php';
                    break;
                case 'create-post':
                    $include = 'views/create-post.php';
                    break;
                case 'admin-page':
                    $include = 'views/admin-page.php';
                    break;
                default: 
                    $include = 'views/home.php';
            }
            $_SESSION['page'] = $_GET['page'];
            return $include;
        }
    }
?>