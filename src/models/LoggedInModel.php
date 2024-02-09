<?php

namespace Project\Models {
    class LoggedInModel
    {
        public $loggedIn;
        public $username;

        public function __construct()
        {
            $this->loggedIn = isset($_SESSION['user_id']);
            $this->username = $_SESSION['username'];
        }
    }
}