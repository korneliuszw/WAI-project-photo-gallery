<?php


namespace Project\Database {

    function get_database()
    {
        static $instance = null;
        if (is_null($instance)) $instance = new \MongoDB\Client('mongodb://localhost/wai', [
            'username' => 'wai_web',
            'password' => 'w@i_w3b'
        ]);
        return $instance->wai;
    }
}