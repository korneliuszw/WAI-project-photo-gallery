<?php

namespace Project\Services {

    use function Project\Database\get_database;

    require_once 'Database.php';
    require_once 'models/PaginatedModel.php';

    class GalleryService
    {
        public $ids;

        public function setLimitedIds($ids)
        {
            $this->ids = $ids;
        }

        private function getQuery($additionalQuery): array
        {
            $query = [
                '$or' => [['private' => ['$exists' => false]], ['author_id' => $_SESSION['user_id']]],
            ];
            if (is_array($this->ids)) {
                $query['_id'] = [
                    '$in' => $this->ids
                ];
            }
            if (is_array($additionalQuery)) $query = array_merge($query, $additionalQuery);
            return $query;
        }

        private function count($db, $additionalQuery = null): int
        {
            $query = $this->getQuery($additionalQuery);
            return $db->photo->count($query);
        }

        private function find($db, $currentPage, $pageSize, $additionalQuery = null)
        {
            $query = $this->getQuery($additionalQuery);
            if (is_array($additionalQuery)) $query = array_merge($query, $additionalQuery);
            return $db->photo->find($query, [
                'sort' => ['_id' => 1],
                'projection' => ['title' => true, 'author' => true, 'private' => true],
                'skip' => $currentPage * $pageSize,
                'limit' => $pageSize
            ]);
        }

        public function getPaginatedGallery($currentPage, $pageSize)
        {
            $db = get_database();
            $total = $this->count($db);

            $result = $this->find($db, $currentPage, $pageSize);
            return [$result->toArray(), $total];
        }

        public function searchPhotosByColumn($currentPage, $pageSize, $phrase): array
        {
            $db = get_database();
            $textQuery = [
                '$text' => [
                    '$search' => $phrase,
                    '$caseSensitive' => false,
                    '$diacriticSensitive' => false
                ]
            ];
            $total = $this->count($db, $textQuery);
            $result = $this->find($db, $currentPage, $pageSize, $textQuery)->toArray();
            return [$result, $total];
        }
    }
}