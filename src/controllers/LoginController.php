<?php

namespace Project\Controllers {


    require_once 'PublicController.php';
    require_once 'Verbs.php';
    require_once 'helpers/guards.php';
    require_once 'services/LoginService.php';
    require_once 'helpers/http.php';
    require_once 'models/LoginModel.php';

    use Project\Helpers\Guards;
    use Project\Models\LoginModel;
    use Project\Services\LoginService;
    use function Project\Helpers\redirect;

    class LoginController extends PublicController implements Verbs\GET, Verbs\POST
    {
        protected $serviceClass = LoginService::class;

        public function GET(&$model)
        {
            Guards\not_logged_in();
            $model = new LoginModel(true, $_GET['registered'] == 'true');
            return 'login';
        }

        public function POST($body, &$model)
        {
            Guards\not_logged_in();
            $user = $this->service->login($body);
            $model = new LoginModel(isset($user));
            if ($model->valid) {
                $_SESSION['user_id'] = $user->_id;
                $_SESSION['roles'] = join(',', $user->roles ?? ['user']);
                $_SESSION['username'] = $user->username;
                redirect('/');
            }
            return 'login';
        }
    }
}