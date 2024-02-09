<?php

namespace Project\Controllers {

    use function Project\Helpers\redirect;

    class LogoutController extends PublicController implements Verbs\GET
    {
        public function GET(&$model)
        {
            setcookie(session_name(), '', 100);
            session_unset();
            session_destroy();
            session_write_close();
            redirect('/');
        }
    }
}