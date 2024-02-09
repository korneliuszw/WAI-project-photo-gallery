<?php

namespace Project\Controllers\Verbs {
    interface POST
    {
        /**
         * @param $requestBody mixed | null decoded json body or null if none was provied;
         */
        function POST($requestBody, &$model);
    }

    interface PUT
    {
        /**
         * @param $requestBody mixed | null decoded json body or null if none was provied;
         */
        function PUT($requestBody, &$model);
    }

    interface GET
    {
        function GET(&$model);
    }

    interface DELETE
    {
        function DELETE();
    }
}