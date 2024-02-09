<?php

namespace Project\Controllers {

    use Project\Services\ProtectedImageService;

    require_once 'PublicController.php';
    require_once 'Verbs.php';
    require_once 'services/ProtectedImageService.php';

    class ProtectedImageController extends PublicController implements Verbs\GET
    {
        protected $serviceClass = ProtectedImageService::class;

        public function GET(&$model)
        {
            $user = $_SESSION["user_id"];
            $imageId = basename($_GET["action"], '.jpeg');
            if (!isset($user) || !isset($imageId) || !$this->service->hasAccess($imageId, $user)) {
                http_response_code(404);
                return null;
            }
            $path = $_SERVER['DOCUMENT_ROOT'] . $_GET['action'];
            $file = fopen($path, 'r');
            if (!$file) {
                http_response_code(404);
                return null;
            }
            $size = filesize($path);
            header('Content-Type: image/jpeg');
            header("Content-Length: $size");
            fpassthru($file);
        }
    }

}
