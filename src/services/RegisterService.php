<?php

namespace Project\Services {

    require_once 'models/RegisterModel.php';
    require_once 'Database.php';

    use function Project\Database\get_database;

    class RegisterService
    {
        public function register($email, $username, $password)
        {
            $client = get_database();
            if (!is_null($client->user->findOne(['username' => $username])))
                throw new \Exception("User with such username already exists");
            $client->user->insertOne(['email' => $email, 'username' => $username, 'password' => password_hash($password, PASSWORD_BCRYPT)]);
        }
    }
}