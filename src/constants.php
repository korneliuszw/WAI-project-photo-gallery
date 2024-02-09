<?php
define("ORIGINAL_IMAGE_PATH", $_ENV['FULL_IMAGE_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . '/images/original/');
define("PRIVATE_ORIGINAL_IMAGE_PATH", $_ENV['FULL_IMAGE_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . '/images/private/original/');

define("PREVIEW_IMAGE_PATH", $_ENV['PREVIEW_IMAGE_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . '/images/preview/');
define("PRIVATE_PREVIEW_IMAGE_PATH", $_ENV['PRIVATE_PREVIEW_IMAGE_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . '/images/private/preview/');

define("IMAGE_PATH", $_ENV['IMAGE_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/');
define("PRIVATE_IMAGE_PATH", $_ENV['PRIVATE_MAGE_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . '/images/private/uploads/');

define("PREVIEW_IMAGE_PATH_SERVED", '/images/preview/');
define("PRIVATE_PREVIEW_IMAGE_PATH_SERVED", '/images/private/preview/');

define("IMAGE_PATH_SERVED", "/images/uploads/");
define("PRIVATE_IMAGE_PATH_SERVED", "/images/private/uploads/");

// Use monospace only!
define("FONT_PATH", $_ENV["FONT_PATH"] ?? $_SERVER["DOCUMENT_ROOT"] . '/public/fonts/IBM_Medium.ttf');