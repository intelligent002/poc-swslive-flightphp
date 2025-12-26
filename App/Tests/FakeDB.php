<?php

    namespace App\Tests;

    use Throwable;

    class FakeDB
    {
        /** @var array<string, array|null|Throwable> */
        private static array $responses = [];

        /**
         * Stub a SQL query to return a value (row or null)
         */
        public static function when( string $sql, array|null $result ) : void
        {
            self::$responses[self::normalize( $sql )] = $result;
        }

        private static function normalize( string $sql ) : string
        {
            return preg_replace( '/\s+/', ' ', trim( $sql ) );
        }

        /**
         * Stub a SQL query to throw an exception
         */
        public static function whenException( string $sql, Throwable $exception ) : void
        {
            self::$responses[self::normalize( $sql )] = $exception;
        }

        /**
         * Fetch stubbed response or throw stubbed exception
         * @throws Throwable
         */
        public static function fetch( string $sql ) : Throwable|array|null
        {
            $normalized = self::normalize( $sql );
            foreach ( self::$responses as $key => $value ) {

                if ( str_starts_with( $normalized, $key ) ) {
                    if ( $value instanceof Throwable ) {
                        throw $value;
                    }
                    return $value;
                }
            }

            return null;
        }

        /**
         * Reset all stubs (recommended in setUp())
         */
        public static function reset() : void
        {
            self::$responses = [];
        }

        /**
         * Optional hook if you log SQL somewhere
         */
        public static function log( $sql, $params ) : void
        {
            // intentionally empty
        }
    }
