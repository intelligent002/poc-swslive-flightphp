<?php

    namespace App\Tests;

    use PDO;
    use Throwable;

    final class FakeStatement
    {
        private string $query;

        public function __construct( string $query )
        {
            $this->query = $query;
        }

        /**
         * @throws Throwable
         */
        public function execute( ?array $params = null ) : bool
        {
            FakeDB::log( $this->query, $params ?? [] );

            // trigger stub resolution & possible exception
            FakeDB::fetch( $this->query );

            return true;
        }

        /**
         * @param int $mode
         * @param int $cursorOrientation
         * @param int $cursorOffset
         * @return array|null
         * @throws Throwable
         */
        public function fetch(
            int $mode = PDO::FETCH_DEFAULT,
            int $cursorOrientation = PDO::FETCH_ORI_NEXT,
            int $cursorOffset = 0
        ) : array|null
        {
            $result = FakeDB::fetch( $this->query );

            if ( $result instanceof Throwable ) {
                throw $result;
            }

            return $result;
        }
    }
