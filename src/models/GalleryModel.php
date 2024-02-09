<?php

namespace Project\Models {

    require_once 'LoggedInModel.php';
    require_once 'constants.php';

    class GalleryItemModel
    {
        public $uploader;
        public $id;
        public $title;
        public $miniatureImagePath;
        public $fullImagePath;
        public $saved = false;
        public $viewSaved;

        public function __construct($document, $saved = false, $viewSaved = false)
        {
            $this->uploader = $document["author"];
            $this->title = $document["title"];
            $this->id = (string)$document["_id"];
            $private = $document['private'];
            $this->miniatureImagePath = ($private ? PRIVATE_PREVIEW_IMAGE_PATH_SERVED : PREVIEW_IMAGE_PATH_SERVED) . $this->id . '.jpeg';
            $this->fullImagePath = ($private ? PRIVATE_IMAGE_PATH_SERVED : IMAGE_PATH_SERVED) . $this->id . '.jpeg';
            $this->saved = $saved == true;
            $this->viewSaved = $viewSaved;
        }
    }

    class GalleryModel extends LoggedInModel
    {
        public $uploads;
        public $pagination;
    }
}