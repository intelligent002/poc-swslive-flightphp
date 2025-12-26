<?php

    namespace App\Infra;

    use PDO;
    use PDOStatement;

    final class PdoConnection implements DBConnection
    {
        /**
         * @var PDO
         */
        private PDO $pdo;

        /**
         * @param PDO $pdo
         */
        public function __construct( PDO $pdo )
        {
            $this->pdo = $pdo;
        }

        /**
         * @param string $query
         * @return bool|PDOStatement
         */
        public function prepare( string $query ) : bool|PDOStatement
        {
            return $this->pdo->prepare( $query );
        }
    }
