<?php

    namespace App\API\v1\Responses;

    class Response
    {
        /**
         * @param mixed $content = []
         * @return array
         */
        public static function OK( mixed $content = [] ) : array
        {
            return [ 'ok' => true, 'data' => $content ];
        }

        /**
         * @param mixed $content = []
         * @return array
         */
        public static function ERROR( mixed $content = [] ) : array
        {
            return [ 'ok' => false, 'errors' => $content ];

        }
    }