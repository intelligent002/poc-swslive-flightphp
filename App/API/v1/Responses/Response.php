<?php

    namespace App\API\v1\Responses;

    class Response
    {
        public static function ok( mixed $content ) : array
        {
            return [ 'ok' => true, 'data' => $content ];
        }

        public static function error( mixed $content ) : array
        {
            return [ 'ok' => false, 'errors' => $content ];

        }
    }