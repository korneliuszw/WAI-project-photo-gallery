<?php
require_once 'vendor/autoload.php';

use Project\Controllers;
use function Project\Helpers\negotiate_mime_type;

require_once 'helpers/json.php';
require_once 'controllers/PublicController.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/RegisterController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/UploadController.php';
require_once 'controllers/GalleryController.php';
require_once 'controllers/ProtectedImageController.php';
require_once 'controllers/LogoutController.php';
require_once 'helpers/http.php';
require_once 'controllers/SavedItemsController.php';

$routes = [
    '/login' => Controllers\LoginController::class,
    '/register' => Controllers\RegisterController::class,
    '/uploader' => Controllers\UploadController::class,
    '/gallery' => Controllers\GalleryController::class,
    '/' => Controllers\GalleryController::class,
    '/logout' => Controllers\LogoutController::class,
    '/saved' => Controllers\SavedItemsController::class
];

$specialRoutes = [
    '/^\/images\/private\/.*\.jpeg/' => Controllers\ProtectedImageController::class
];


function get_route_controller(string $path, array $segment_objects, array $regexRoutes): Controllers\PublicController
{
    if (array_key_exists($path, $segment_objects))
        return new $segment_objects[$path]();

    foreach (array_keys($regexRoutes) as $route) {
        if (preg_match($route, $path)) {
            return new $regexRoutes[$route]();
        }
    }
    http_response_code(404);
    exit(0);
}

$controller = get_route_controller($_REQUEST['action'], $routes, $specialRoutes);
session_start();
$model = [];
$view = $controller->request($model);

// Controller returned a view, so we have to display it
$responseType = negotiate_mime_type(['text/html', 'application/json'], 'text/html');
if ($responseType == 'text/html' && is_string($view)) {
    include 'views/' . $view . '.php';
} // Otherwise, model without view means we want to respond with the data but don't display any HTML
else if (isset($model) && (!is_array($model) || count($model) > 0)) {
    \Project\Helpers\JSON\send_json($model);
}
