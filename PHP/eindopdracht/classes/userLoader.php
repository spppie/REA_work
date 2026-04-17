<?php
    class userLoader {
        // properties
        private string $resource;
        private SimpleXMLElement $userdata;

        // constructor
        public function __construct(string $resource) {
            $this->resource = $resource;
            $this->userdata = new SimpleXMLElement($this->resource, 0, true);
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
        public function loadAllUsers() {
            $return = array();
            foreach($this->userdata->user as $user) {
                array_push($return, array(
                    'id' => $user['id'],
                    'username' => $user->username,
                    'email' => $user->email,
                    'img' => $user->img
                ));
            }
            return $return;
        }
    }
?>