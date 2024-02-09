<?php

namespace Project\Controllers {

    use MongoDB\BSON\ObjectId;
    use function Project\Helpers\send_client_error;

    require_once 'GalleryController.php';
    require_once 'Verbs.php';

    class SavedItemsController extends GalleryController implements Verbs\POST
    {

        public function __construct()
        {
            parent::__construct();
        }

        function POST($body, &$model)
        {
            if (!isset($body['photoId'])) return send_client_error("photoId must be a string", "Please provide ID of a photo you want to save", 'gallery', $model, 400);
            if (is_array($body['photoId'])) {
                if (is_null($_SESSION['saved_item'])) $_SESSION['saved_item'] = [];
                if ($body['forget'])
                    $_SESSION['saved_item'] = array_values(array_diff($_SESSION['saved_item'], $body['photoId']));
                else $_SESSION['saved_item'] = array_values(array_unique(array_merge($_SESSION['saved_item'], $body['photoId'])));
            }
            return null;
        }

        function GET(&$model)
        {
            $objectIds = array_map(function ($x) {
                return new ObjectId($x);
            }, $_SESSION['saved_item'] ?: []);
            $this->service->setLimitedIds($objectIds);
            $return = parent::GET($model);
            $model->view_saved = true;
            return $return;
        }
    }
}