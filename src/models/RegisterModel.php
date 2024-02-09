<?php

namespace Project\Models {
    class RegisterModel
    {
        public $error;
        public $valid;

        public function __construct($valid, $errorMessage)
        {
            $this->error = $errorMessage;
            $this->valid = $valid;
        }
    }
}