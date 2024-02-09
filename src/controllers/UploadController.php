<?php

namespace Project\Controllers {

    use Project\Models\LoggedInModel;
    use Project\Services\UploadService;
    use function Project\Helpers\is_multipart_request;
    use function Project\Helpers\redirect;
    use function Project\Helpers\send_client_error;

    require_once 'models/LoggedInModel.php';
    require_once 'helpers/http.php';
    require_once 'helpers/json.php';
    require_once 'services/UploadService.php';

    class UploadController extends PublicController implements Verbs\GET, Verbs\POST
    {
        protected $serviceClass = UploadService::class;

        public function GET(&$model): string
        {
            $model = new LoggedInModel();
            return 'uploader';
        }

        function POST($body, &$model)
        {
            $defaultView = 'uploader';
            if (is_null($body['files']) || count($body['files']) == 0 || !is_multipart_request()) {
                return send_client_error('Missing file to upload. Attach with multipart/form-data', 'Oops! You forgot to attach a file.', $defaultView, $model);
            } else if (count($body['files']) != 1) {
                return send_client_error('This endpoint accepts exactly one file', 'You can only upload one file at a time', $defaultView, $model);
            } else if ($body['files']['file']['error'] != 0) {
                if ($body['files']['file']['error'] == 1) {
                    $maxFileSize = ini_get('upload_max_filesize');
                    return send_client_error("File too big. Maximum file size is $maxFileSize}", "Your file is too large! We only accept files below $maxFileSize", $defaultView, $model);
                }
                return send_client_error("Unknown error: {$body["files"]['file']["error"]}", "Something wrong has happened. Please try again or contact support.", $defaultView, $model, 500);
            } else if (strlen($body['body']['watermark']) < 1 || strlen($body['body']['watermark']) > 130) {
                return send_client_error("Watermark must be between 1 and 130 characters long", "Watermark is required must be no longer than 130 characters. Sorry :(", $defaultView, $model);
            }
            try {
                $this->service->upload($body['files']['file'], $body['body']['author'], $body['body']['watermark'], $body['body']['title'], $body['body']['visibility'] == 'private');
            } catch (\Exception $err) {
                if ($err->getCode() == 401) {
                    return send_client_error("Unsupported file format, only jpg and png files", "Sorry, but we only accept .jpeg, .jpg or .png images.", $defaultView, $model);
                }
                return send_client_error("Unknown error", "Something wrong has happened. Please try again or contact support.", $defaultView, $model, 500);
            }
            redirect('/gallery');
            return null;
        }
    }
}