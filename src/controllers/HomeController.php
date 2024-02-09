<?php

namespace Project\Controllers {

    use Project\Models\LoggedInModel;

    require_once 'PublicController.php';
    require_once 'models/LoggedInModel.php';
    require_once 'Verbs.php';

    class HomeController extends PublicController implements Verbs\GET
    {
        public function GET(&$model)
        {
            $model = new LoggedInModel();
            return 'index';
        }
    }
}