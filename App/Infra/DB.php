<?php

    namespace App\Infra;

    use LogicException;

    final class DB
    {
        private static ?DBConnection $conn = null;

        public static function set( DBConnection $conn ) : void
        {
            self::$conn = $conn;
        }

        public static function get() : DBConnection
        {
            if ( !self::$conn ) {
                throw new LogicException( 'DB connection not initialized' );
            }

            return self::$conn;
        }
    }