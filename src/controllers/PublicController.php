<?php


namespace Project\Controllers {

    require_once 'Verbs.php';
    require_once 'helpers/json.php';
    require_once 'helpers/http.php';

    use Project\Helpers\JSON;
    use function Project\Helpers\is_form_request;
    use function Project\Helpers\is_json_request;
    use function Project\Helpers\is_multipart_request;

    function invalid_method_response()
    {
        http_response_code(404);
        JSON\send_json(["error" => "INVALID_METHOD"]);
    }

    /**
     * @method GET(&$model);
     * @method POST(mixed|null $body, &$model)
     * @method PUT(mixed|null $body, &$model)
     * @method DELETE();
     */
    abstract class PublicController
    {
        protected $serviceClass;
        protected $service;

        public function __construct()
        {
            if (isset($this->serviceClass)) $this->service = new $this->serviceClass();
        }

        private function get_body()
        {
            $body = null;

            if (is_json_request())
                $body = json_decode(file_get_contents('php://input'), true);
            else if (is_form_request())
                $body = $_POST;
            else if (is_multipart_request()) {
                $body = [
                    'files' => $_FILES,
                    'body' => $_POST
                ];
            } else {
                http_response_code(400);
                JSON\send_json(['error' => 'Content-Type not supported']);
            }
            return $body;
        }

        public function request(&$model)
        {
            $impl = class_implements($this);
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                {
                    if (array_key_exists(Verbs\GET::class, $impl)) {
                        return $this->GET($model);
                    }
                    http_response_code(404);
                    break;
                }
                case 'POST':
                {
                    if (array_key_exists(Verbs\POST::class, $impl)) {
                        return $this->POST($this->get_body(), $model);
                    }
                    invalid_method_response();
                }
                case 'PUT' :
                {
                    if (array_key_exists(Verbs\PUT::class, $impl)) {
                        return $this->PUT($this->get_body(), $model);
                    }
                    invalid_method_response();
                }
                case 'DELETE':
                {
                    if (array_key_exists(Verbs\DELETE::class, $impl)) {
                        return $this->DELETE();
                    }
                    invalid_method_response();
                }
            }
            return 0;
        }
    }
}