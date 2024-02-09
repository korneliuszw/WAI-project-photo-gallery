<?php

namespace Project\Helpers {

    use Project\Models\ErrorModel;
    use function Project\Helpers\JSON\send_json;

    require_once 'models/ErrorModel.php';

    function redirect(string $absolutePath)
    {
        // 302 because permanent redirects should be handled by apache
        header("Location: " . $absolutePath, true, 302);
        die();
    }

    function get_content_type()
    {
        return getallheaders()['Content-Type'];
    }

    function is_json_request()
    {
        return get_content_type() == 'application/json';
    }

    function is_form_request()
    {
        return get_content_type() == 'application/x-www-form-urlencoded';
    }

    function str_starts_with($str, $search)
    {
        return substr($str, 0, strlen($search)) == $search;
    }

    function is_multipart_request()
    {
        return str_starts_with(get_content_type(), 'multipart/form-data');
    }

    function negotiate_mime_type($accepted, $fallback): string
    {
        $acceptedMap = array_fill_keys($accepted, true);
        $headerValues = explode(',', getallheaders()['Accept']);
        $topMime = [];
        foreach ($headerValues as $headerValue) {
            $headerValue = str_replace(' ', '', $headerValue);
            $parts = explode(';', $headerValue);
            $mime = $parts[0];
            if (!$acceptedMap[$mime]) continue;
            $q = 1;
            if ($parts[1]) {
                $i = 0;
                while ($parts[1][$i] != '=')
                    $i++;
                $q = intval(substr($parts[1], $i + 1));
            }
            if ($topMime[0] > $q) break;
            $topMime = [$q, $mime];

        }
        return $topMime[1] ?: $fallback;
    }

    function send_client_error($jsonError, $humanError, $view, &$model, $responseCode = 400)
    {
        switch (negotiate_mime_type(['application/json', 'text/html'], 'application/json')) {
            case 'application/json':
            {
                http_response_code($responseCode);
                send_json(['error' => $jsonError]);
                return null;
            }
            case 'text/html':
            {
                $model = new ErrorModel();
                $model->error = $humanError;
                return $view;
            }
        }
    }
}