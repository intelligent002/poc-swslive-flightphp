<?php

    namespace App\Infra;

    interface DBConnection
    {
        /**
         * @param string $query
         * @return mixed
         */
        public function prepare( string $query );
    }
