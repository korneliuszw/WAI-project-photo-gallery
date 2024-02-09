<?php

namespace Project\Services {

    use MongoDB\BSON\ObjectId;
    use function Project\Database\get_database;

    require_once 'Database.php';

    class ProtectedImageService
    {
        public function hasAccess($photoId, $user)
        {
            $result = get_database()->photo->findOne([
                "_id" => new ObjectId($photoId),
                "author_id" => $user,
                "private" => true
            ]);
            return isset($result);
        }
    }
}