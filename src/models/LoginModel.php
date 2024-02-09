<?php

namespace Project\Models {
    class LoginModel
    {
        public $error;
        public $successMessage;
        public $valid;

        public function __construct($valid = true, $registered = false)
        {
            if (!$valid) $this->error = "Wrong login or password!";
            if ($registered) $this->successMessage = "You have been registered. You can now login to your new account";
            $this->valid = $valid;
        }
    }
}