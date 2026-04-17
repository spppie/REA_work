<?php

use Jouw\Namespace\Hier\mailHelper;

    class postManager {
        // properties
        private string $resource;
        private SimpleXMLElement $postdata;
        private array $blacklist;

        // constructor
        public function __construct(string $resource, array $blacklist) {
            $this->resource = $resource;
            $this->blacklist = $blacklist;
            $this->postdata = new SimpleXMLElement($this->resource, 0, true);
        }

        // getter
        public function getResource() : string {
            return $this->resource;
        }
        
        // setter
        public function setResource(string $resource) {
            $this->resource = $resource;
            $this->postdata = new SimpleXMLElement($this->resource);
        }

        // functions
        public function loadInAllPosts() {
            $return = array();
            foreach ($this->postdata->post as $post) {
                array_push($return, array(
                    'id' => $post['id'],
                    'date' => $post['date'],
                    'author' => $post->author,
                    'email' => $post['email'],
                    'title' => $post->title,
                    'content' => $post->content,
                    'comments' => $post->comments
                ));
            }
            return array_reverse($return);
        }

        public function compareToBlacklist(array $dataArray) : bool {
            foreach($this->blacklist as $blaclistEntry) {
                foreach ($dataArray as $dataEntry) {
                    if (str_contains(strtolower($dataEntry), strtolower($blaclistEntry))) {
                        return true;
                    }
                }
            }
            return false;
        }

        public function displayAllPostInArray(array $postArray) {
            $returnString = '';
            $postCounter = 0;
            if (isset($_SESSION['sort-by'])) {
                if ($_SESSION['sort-by'] === 'date-desc') {
                    $postArray = array_reverse($postArray);
                }
                if ($_SESSION['sort-by'] === ('author-A-Z' || 'author-Z-A')) {}
                if ($_SESSION['sort-by'] === 'author-A-Z' || $_SESSION['sort-by'] === 'author-Z-A') {
                    usort($postArray, function ($post1, $post2) {
                        // var_dump($post1['author']);
                        return (string)$post1['author'] <=> (string)$post2['author'];
                    });
                    if ($_SESSION['sort-by'] === 'author-Z-A') {
                        $postArray = array_reverse($postArray);
                    }
                }
            }
            foreach($postArray as $post) {
                if (!($_SESSION['sort-amount'] === 'all')) {
                    if ($postCounter === (int)$_SESSION['sort-amount']) {
                        break;
                    }
                }
                $commentXML = new SimpleXMLElement('xml/commentdata.xml',0, true);
                $commentArray = array();
                foreach($commentXML->comment as $comment) {

                    if ((string)$comment->postid === (string)$post['id']) {
                        array_push($commentArray, (array)$comment);
                    }
                }
                $commentCount = count($commentArray);
                $returnString .=
                    '<section class="post-list-section border-right border-bottom outside-padding">
                        <div class="post-title-div">
                            <h2>'.$post['title'].'</h2>
                            <p>'.$post['author'].'</p>
                        </div>
                        <p>'.substr($post['content'], 0, 100).'</p>
                        <p>date: '.$post['date'].'</p>
                        <p>comments: '.$commentCount.'</p>
                        <a href="home/'.$post['id'].'">open post</a>
                    </section>'
                ;
                $postCounter++;
            }
            return $returnString;
        }

        public function displaySpecPost(array $postArray) : string {
            foreach($postArray as $post) {
                if ((string)$post['id'] === $_GET['postid']) {
                    $detailString = '';
                
                    $commentXML = new SimpleXMLElement('xml/commentdata.xml',0, true);
                    $commentArray = array();
                    foreach($commentXML->comment as $comment) {
                        if ((string)$comment->postid === (string)$_GET['postid']) {
                            array_push($commentArray, (array)$comment);
                        }
                    }
                    $commentString = '<div id="comment-area">';
                    foreach($commentArray as $comment) {
                        $delCommString = '';
                        if ($_SESSION['logged'] && ((string)$post['author'] === $_SESSION['user'] || $_SESSION['user'] === 'admin' || $_SESSION['user'] === $comment['author'])) {
                            $delCommString = '<form id="comment-delete-form" method="post" action="home/'.$_GET['postid'].'"><input type="hidden" name="delete-comment-id" value="'.$comment['commentid'].'">';
                            if ($_SESSION['logged'] && (string)$_SESSION['user'] === 'admin') {
                                $delCommString .= '<input type="text" name="comment-delete-reason" id="comment-delete-reason" placeholder="mod reason:" required>';
                            }
                            $delCommString .= '<input type="submit" value="delete comment" id="delete-comment" name="delete-comment"></form>';
                        }
                        $commentString .= '<section class="comment outside-padding"<p>'.$comment['content'].'</p><p>author: '.$comment['author'].'</p><p>'.$comment['@attributes']['date'].'</p>'.$delCommString.'</section>';
                    }
                    $commentString .= '</div>';
                    $delPostString = '';
                    $editPostString = '';
                    if ($_SESSION['logged'] && ((string)$post['author'] === $_SESSION['user'] || $_SESSION['user'] === 'admin')) {
                        $delPostString = '<form id="post-delete-form" method="post" action="home/'.$_GET['postid'].'"><input type="hidden" name="delete-post-id" value="'.$_GET['postid'].'">';
                        $editPostString = '<form id="post-edit-form-button" method="post" action="create-post/'.$_GET['postid'].'"><input type="hidden" name="edit-post-id" value="'.$_GET['postid'].'">';
                        if ($_SESSION['logged'] && (string)$_SESSION['user'] === 'admin') {
                            $delPostString .= '<input type="text" name="post-delete-reason" id="post-delete-reason" placeholder="mod reason:" required>';
                        }
                        $delPostString .= '<input type="submit" value="delete post" id="delete-post" name="delete-post"></form>';
                        $editPostString .= '<input type="submit" value="edit post" id="edit-post" name="edit-post"></form>';
                    }
                    // if ($_SESSION['logged'] && ($_SESSION['logged']))
                    if ($_SESSION['logged']) {
                        $detailString = 
                            '<div id="spec-post-comment-div" class="outside-padding">
                                <details>
                                    <summary>leave a comment:</summary>
                                    <form id="comment-create-form" method="post" action="home/'.$_GET['postid'].'">
                                        <input type="hidden" name="post-email" value="'.$post['email'].'">
                                        <label for="comment-text-area">comment:</label>
                                        <textarea name="comment-text-area" id="comment-text-area"></textarea>
                                        <input type="submit" value="create-comment" id="create-comment" name="create-comment">
                                    </form>
                                </details>
                            </div>'
                        ;
                    }
                    return 
                        '<div id="spec-post-div" class="outside-padding">
                            <h1>'.$post['title'].'</h1>
                            <p>'.$post['content'].'</p><br>
                            <p>author: '.$post['author'].', post ID: '.$post['id'].'</p>
                            <p>'.$post['date'].'</p>
                            <section class="post-buttons">'. $delPostString . $editPostString . '</section
                        </div>'
                        .$detailString
                        .$commentString
                    ;
                }
            }
            return '<p>invalid post id.</p>';
        }

        public function createComment() {
            $commentDataArray = array((string)$_SESSION['user'], (string)$_POST['comment-text-area']);
            if ($this->compareToBlacklist($commentDataArray)) {
                return '<p>non allowed words used in comment</p>';
            }
            $xml = new SimpleXMLElement('xml/commentdata.xml', 0, true);
            $entry = count($xml);
            $xml->addChild('comment');
            $xml->comment[$entry]->addAttribute('date', date("H:i:s-Y-m-d"));
            $xml->comment[$entry]->addChild('postid', $_GET['postid']);
            $xml->comment[$entry]->addChild('commentid', uniqid());
            $xml->comment[$entry]->addChild('author', $commentDataArray[0]);
            $xml->comment[$entry]->addChild('content', $commentDataArray[1]);
            $xml->comment[$entry]->addChild('ipaddr', $_SERVER['REMOTE_ADDR']);
            $xml->asXML('xml/commentdata.xml');

            // comment to not use all the allowed emails
            // mailHelper::sendMail($commentDataArray[0], $commentDataArray[1], $_POST['post-email']);

            return '<p>comment created succesfully</p>';
        }

        public function deleteComment() {
            $commentXML = new SimpleXMLElement('xml/commentdata.xml',0, true);
            foreach($commentXML->comment as $comment) {
                if ((string)$comment->commentid === (string)$_POST['delete-comment-id']) {
                    $comment->content = 'deleted by post creator';
                    if ((string)$_SESSION['user'] === (string)$comment->author) {
                        $comment->content = 'deleted by creator of comment';
                    } 
                    if ((string)$_SESSION['user'] === 'admin') {
                        $comment->content = 'comment was deleted by admin for reason: ' . $_POST['comment-delete-reason'];
                    }
                    $commentXML->asXML('xml/commentdata.xml');
                }
            }
        }

        public function createPost() {
            $xml = new SimpleXMLElement('xml/postdata.xml', 0, true);
            $postDataArray = array(
                (string)$_SESSION['user'], 
                (string)$_SESSION['user-email'],
                (string)$_POST['post-create-title'], 
                (string)$_POST['post-text-area']);
            if ($this->compareToBlacklist($postDataArray)) {
                return '<p>non allowed words used in post</p>';
            }
            $entry = count($xml);
            $xml->addChild('post');
            $xml->post[$entry]->addAttribute('id', uniqid());
            $xml->post[$entry]->addAttribute('date', date("H:i:s-Y-m-d"));
            $xml->post[$entry]->addChild('author', $postDataArray[0]);
            $xml->post[$entry]->addAttribute('email', $postDataArray[1]);
            $xml->post[$entry]->addChild('title', $postDataArray[2]);
            $xml->post[$entry]->addChild('content', $postDataArray[3]);
            $xml->asXML('xml/postdata.xml');
            return '<p>post created succesfully</p>';
        }

        public function editPost($postArray) {
            $xml = new SimpleXMLElement('xml/postdata.xml', 0, true);
            $postDataArray = array((string)$_SESSION['user'], (string)$_POST['post-edit-title'], (string)$_POST['post-text-area']);
            if ($this->compareToBlacklist($postDataArray)) {
                return '<p>non allowed words used in post</p>';
            }
            if (isset($_POST['postid'])) {
                $postXML = new SimpleXMLElement('xml/postdata.xml',0, true);
                foreach($postXML->post as $post) {
                    if ((string)$post['id'] === (string)$_POST['postid']) {
                        $post->title = $postDataArray[1];
                        $post->content = $postDataArray[2];
                        $post['date'] = date("H:i:s-Y-m-d");
                        $postXML->asXML('xml/postdata.xml');
                        return '<p>post edited succesfully</p>';
                    }
                }
            } else {
                return '<p>cannot edit invalid post id</p>';
            }
        }

        public function deletePost() {
            $postXML = new SimpleXMLElement('xml/postdata.xml',0, true);
            $counter = 0;
            foreach($postXML->post as $post) {
                if ((string)$post['id'] === (string)$_POST['delete-post-id']) {
                    // $post->title = 'deleted post';
                    // $post->content = 'deleted by user';
                    // if ((string)$_SESSION['user'] === (string)$post->author) {
                    //     $post->content = 'deleted by creator of post';
                    // } 
                    // if ((string)$_SESSION['user'] === 'admin') {
                    //     $post->content = 'post was deleted by admin for reason: ' . $_POST['post-delete-reason'];
                    // }
                    unset($postXML->post[$counter]);
                    $postXML->asXML('xml/postdata.xml');
                }
                $counter++;
            }
        }

        public function postSearch(array $postArray) : string {
            $searchArray = array();
            switch($_POST['search-type']) {
                case 'author':
                    foreach($postArray as $post) {
                        if (str_contains(strtolower($post['author']), strtolower($_POST['query-string']))) {
                            array_push($searchArray, $post);
                        }
                    }
                    break;
                case 'title':
                    foreach($postArray as $post) {
                        if (str_contains(strtolower($post['title']), strtolower($_POST['query-string']))) {
                            array_push($searchArray, $post);
                        }
                    }
                    break;
                case 'content':
                    foreach($postArray as $post) {
                        if (str_contains(strtolower($post['content']), strtolower($_POST['query-string']))) {
                            array_push($searchArray, $post);
                        }
                    }
                    break;
            }
            if (!count($searchArray)) {
                return '<p>Unfortunately your search didnt have any results.</p>';
            } else {
                return $this->displayAllPostInArray($searchArray);
            }
        }
        public function showPostsProfile(array $postArray) {
            $returnArray = '';
            foreach($postArray as $post) {
                if ((string)$post['author'] === $_GET['username']) {
                    $commentXML = new SimpleXMLElement('xml/commentdata.xml',0, true);
                    $commentArray = array();
                    foreach($commentXML->comment as $comment) {

                        if ((string)$comment->postid === (string)$post['id']) {
                            array_push($commentArray, (array)$comment);
                        }
                    }
                    $commentCount = count($commentArray);
                    $returnArray .=
                        '<section class="post-list-section border-right border-bottom outside-padding">
                            <div class="post-title-div">
                                <h2>'.$post['title'].'</h2>
                                <p>'.$post['author'].'</p>
                            </div>
                            <p>'.$post['content'].'</p>
                            <p>date: '.$post['date'].'</p>
                            <p>comments: '.$commentCount.'</p>
                            <a href="home/'.$post['id'].'">open post</a>
                        </section>'
                    ;
                }
            }
            return $returnArray;
        }
    }

?>