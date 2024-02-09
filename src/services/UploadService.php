<?php

namespace Project\Services {


    require_once 'Database.php';
    include_once 'constants.php';

    use function Project\Database\get_database;

    class Watermark
    {
        private $maxHeight;
        private $maxWidth;
        private $x;
        private $y;
        private $fontSize = 100;

        /**
         * @param $maxWidth int percentage width
         * @param $maxHeight int percentage height
         * @param $x int percentage x coordinate of first letter
         * @param $y int percentage y coordinate of first letter
         */
        public function __construct($maxWidth, $maxHeight, $x, $y)
        {
            $this->maxWidth = $maxWidth / 100;
            $this->maxHeight = $maxHeight / 100;
            $this->x = $x / 100;
            $this->y = $y / 100;
        }

        private function getFontDimensions($fontSize, $text = "A"): array
        {
            $arr = imageftbbox($fontSize, 0, FONT_PATH, $text);
            return [
                "width" => $arr[2] - $arr[0],
                "height" => $arr[1] - $arr[7],
            ];
        }

        private function calculateCharactersPerLine($fontSize, $maxWidth)
        {
            return floor($maxWidth / ($this->getFontDimensions($fontSize)["width"]));
        }

        /**
         * Calculate base font size to fit inside given boundaries
         */
        private function calculateOptimalFontSize($text, $maxHeight, $maxWidth, $guessFontSize = 15)
        {
            $wrapped = wordwrap($text, $this->calculateCharactersPerLine($guessFontSize, $maxWidth), "\n", true);
            $dimensions = $this->getFontDimensions($guessFontSize, $wrapped);
            // Based on https://math.stackexchange.com/questions/857073/formula-for-adjusting-font-height
            $optimal = floor($guessFontSize * sqrt(
                    $maxHeight / $dimensions["height"]));
            return min($optimal, $this->fontSize);
        }


        public function print($text, &$image)
        {
            // cast percentage value into image dimensions
            $maxHeight = $this->maxHeight * imagesy($image);
            $maxWidth = $this->maxWidth * imagesx($image);
            $x = $this->x * imagesx($image);
            $y = $this->y * imagesy($image);
            $size = $this->calculateOptimalFontSize($text, $maxHeight, $maxWidth);
            $texts = wordwrap($text, $this->calculateCharactersPerLine($size, $maxWidth), "\n", true);
            $color = imagecolorallocatealpha($image, 0, 0, 0, 30);
            $dimensions = $this->getFontDimensions($size, $texts);
            // align to end of box
            if ($dimensions["height"] < $maxHeight) {
                $y += $maxHeight - $dimensions['height'];
            }
            if ($dimensions["width"] < $maxWidth) {
                $x += $maxWidth - $dimensions['width'];
            }
            imagefttext($image, $size, 0, $x, $y, $color, FONT_PATH, $texts);
        }
    }

    function scale_image($image)
    {
        return imagescale($image, 200, 125);
    }

    function save_image($image, $path, $quality = 100, $move = false)
    {
        // create path recursively to make sure we can write there
        mkdir($path, 0755, true);
        // delete the directory - we want it to be a file :D
        rmdir($path);
        // Create the file
        fclose(fopen($path, 'x'));
        if (!$move)
            imagejpeg($image, $path, $quality);
        else
            move_uploaded_file($image, $path);
    }

    class UploadService
    {

        private $watermarkFactory;

        public function __construct()
        {
            $this->watermarkFactory = new Watermark(25, 15, 74, 84);
        }

        private function fileUpload($uploadId, $file, $watermark, $private)
        {
            $image = null;
            $path = $file['tmp_name'];
            switch ($file['type']) {
                case 'image/png':
                {
                    $image = imagecreatefrompng($path);
                    break;
                }
                case 'image/jpeg':
                {
                    $image = imagecreatefromjpeg($path);
                    break;
                }
                default:
                {
                    throw new \Exception('INVALID_FILE_TYPE', 401);
                }
            }
            if (is_string($watermark)) {
                $this->watermarkFactory->print($watermark, $image);
            }
            save_image($image, ($private ? PRIVATE_IMAGE_PATH : IMAGE_PATH) . $uploadId . '.jpeg', 80);
            save_image(scale_image($image), ($private ? PRIVATE_PREVIEW_IMAGE_PATH : PREVIEW_IMAGE_PATH) . $uploadId . '.jpeg', 75);
            imagedestroy($image);
            save_image($path, ($private ? PRIVATE_ORIGINAL_IMAGE_PATH : ORIGINAL_IMAGE_PATH) . $uploadId . '.jpeg', 100, true);
        }

        public function upload($file, $author, $watermark, $title, $private)
        {
            $client = get_database();
            $query = ['author' => $_SESSION['username'] ?: $author,
                'author_id' => $_SESSION['user_id'],
                'original_name' => $file['name'],
                "title" => $title];
            if ($_SESSION["user_id"] && $private == "on") {
                $query["private"] = true;
            }
            $created_photo = $client->photo->insertOne($query);
            $client->photo->createIndex([
                "private" => 1,
                "author_id" => 1,
            ]);
            $client->photo->createIndex([
                "title" => "text"
            ]);
            $id = $created_photo->getInsertedId();
            try {
                self::fileUpload($id, $file, $watermark, $query["private"]);
            } catch (\Exception $ex) {
                $client->photo->deleteOne(['_id' => $id]);
                throw $ex;
            }
        }
    }
}