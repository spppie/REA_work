<?php
    class userManager {
        // properties
        private string $resource;
        private SimpleXMLElement $userdata;

        // constructor
        public function __construct(string $resource) {
            $this->resource = $resource;
            $this->userdata = new SimpleXMLElement($this->resource, 0, true);
            if (!isset($_SESSION['logged'])) {
                $_SESSION['logged'] = false;
            }
            if (!isset($_SESSION['inval'])) {
                $_SESSION['inval'] = false;
            }
        }

        // getter
        public function getResource() : string {
            return $this->resource;
        }
        
        // setter
        public function setResource(string $resource) {
            $this->resource = $resource;
            $this->userdata = new SimpleXMLElement($this->resource);
        }

        // functions
        public function showUserData(string $username, string $password) {
            if ((string)$this->userdata->user[0]->username === $username && (string)$this->userdata->user[0]->password === $password) {
                echo '<pre>';
                var_dump($this->userdata);
            } else {
                echo 'cannot access userdata without admin login, username or password incorrect';
            }
        }

        public function login(string $username, string $password) {
            // echo '<pre>';
            foreach($this->userdata->user as $user) {
                if ((string)$user->username === $username && (string)$user->password === $password) {
                    $_SESSION['logged'] = true;
                    $_SESSION['user'] = (string)$user->username;
                    $_SESSION['user-id'] = (string)$user['id'];
                    $_SESSION['user-email'] = (string)$user->email;
                }
            }
            if ($_SESSION['logged'] === true) {
                $_SESSION['inval'] = false;
            } else {
                $_SESSION['inval'] = true;
            }
        }

        public function logout() {
            $_SESSION['logged'] = false;
            unset($_SESSION['user']);
            unset($_SESSION['user-id']);
        }

        public function getAccount(string $source) : string {
            if ($source === 'id') {
                foreach($this->userdata->user as $user) {
                    if ((string)$user['id'] === $_GET['user-id']) {
                        // echo '<p>showing page of: '.$_GET['id'].' using ID</p>';
                        return 
                            '<section id="profile-section" class="outside-padding">
                                <img src="'.$user->img.'" alt="profile photo of '.$user->username.'">
                                <div>
                                    <h2>'.$user->username.'</h2>
                                    <p>'.$user->email.'</p>
                                </div>
                            </section>
                            <section>
                            </section>'
                        ;
                    }
                }
            } elseif ($source === 'user') {
                foreach($this->userdata->user as $user) {
                    if ((string)$user->username === $_GET['username']) {
                        // echo '<p>account page of: '.$_SESSION['user'].' using user</p>';
                        return 
                            '<section id="profile-section" class="outside-padding">
                                <img src="'.$user->img.'" alt="profile photo of '.$user->username.'">
                                <div>
                                    <h2>'.$user->username.'</h2>
                                    <p>'.$user->email.'</p>
                                </div>
                            </section>'
                        ;
                    }
                }
            }
            return '<p>something went wrong with your request</p>';
        }
    }
?>
