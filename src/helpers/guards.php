<?php

namespace Project\Helpers\Guards {
    require_once 'http.php';

    use Project\Helpers;

    function not_logged_in()
    {
        if (isset($_SESSION['user_id'])) Helpers\redirect('/');
    }
}