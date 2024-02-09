<?php

namespace Project\Controllers {

    use Project\Models\GalleryItemModel;
    use Project\Models\GalleryModel;
    use Project\Models\PaginatedModel;
    use Project\Services\GalleryService;

    require_once 'PublicController.php';
    require_once 'Verbs.php';
    require_once 'services/GalleryService.php';
    require_once 'models/GalleryModel.php';

    class GalleryController extends PublicController implements Verbs\GET
    {
        protected $serviceClass = GalleryService::class;

        public function GET(&$model)
        {
            $phrase = $_GET['searchPhrase'];
            $pageSize = is_numeric($_GET['pageSize']) ? intval($_GET['pageSize']) : 4;
            $page = is_numeric($_GET['page']) ? intval($_GET['page']) : 0;
            $result = strlen($phrase) > 0 ?
                $this->service->searchPhotosByColumn($page, $pageSize, $phrase)
                : $this->service->getPaginatedGallery($page, $pageSize);
            $model = new GalleryModel();
            $model->uploads = array_map(function ($item) {
                $saved = isset($_SESSION['saved_item']) && in_array((string)$item['_id'], $_SESSION['saved_item']);
                return new GalleryItemModel($item, $saved);
            }, $result[0]);
            $model->pagination = new PaginatedModel($page, $pageSize, $result[1], $phrase);
            return 'gallery';
        }

    }
}