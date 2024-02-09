<?php

namespace Project\Helpers\JSON {
    function send_json($value)
    {
        if (!headers_sent())
            header('Content-Type: application/json; charset=utf-8');
        echo json_encode($value);
    }
}