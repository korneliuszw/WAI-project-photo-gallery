<?php

namespace Project\Controllers {

    require_once 'Verbs.php';
    require_once 'helpers/guards.php';
    require_once 'services/RegisterService.php';
    require_once 'helpers/http.php';

    use Project\Helpers\Guards;
    use Project\Models\RegisterModel;
    use Project\Services\RegisterService;
    use function Project\Helpers\redirect;

    class RegisterController extends PublicController implements Verbs\POST, Verbs\GET
    {
        protected $serviceClass = RegisterService::class;

        public function GET(&$model): string
        {
            Guards\not_logged_in();
            return "register";
        }

        public function POST($body, &$model): string
        {
            Guards\not_logged_in();
            $email = $body['email'];
            $username = $body['username'];
            $password = $body['password'];
            $repeatedPassword = $body['repeatedPassword'];
            if (is_null($email) || is_null($username) || is_null($password) || is_null($repeatedPassword)) {
                $model = new RegisterModel(false, "Missing one of required fields: username, password, repeated password");
            } else if ($password != $repeatedPassword) {
                $model = new RegisterModel(false, "Passwords aren't the same");
            } else if (strlen($password) < 8 || strlen($password) >= 64) {
                $model = new RegisterModel(false, "Your password has to be between 8 to 64 characters long ");
            } else {
                try {
                    $this->service->register($email, $username, $password);
                    $model = new RegisterModel(true, null);
                } catch (\Exception $err) {
                    $model = new RegisterModel(false, $err->getMessage());
                }
            }
            if ($model->valid)
                redirect('/login?registered=true');
            return 'register';
        }
    }
}