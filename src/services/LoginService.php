<?php

namespace Project\Services {

    require_once 'Database.php';


    use Project\Database;

    class LoginService
    {
        public function login($loginData)
        {
            if (is_null($loginData['username']) || is_null($loginData['password'])) {
                return null;
            }
            $client = Database\get_database();
            $user = $client->user->findOne(['username' => $loginData['username']]);
            if (!(isset($user) && password_verify($loginData['password'], $user->password))) {
                return null;
            }
            return $user;
        }
    }
}