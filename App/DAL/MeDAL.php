<?php

    namespace App\DAL;

    use App\Infra\DB;
    use PDO;
    use PDOException;
    use Ramsey\Uuid\Uuid;

    class MeDAL
    {
        /**
         * @param array $data
         * @return void
         * @throws PDOException
         */
        public static function create( array $data ) : void
        {
            $stmt = DB::get()->prepare(
                '
                INSERT INTO users 
                SET  
                    id=?,
                    name=?,
                    email=?,
                    date_of_birth=?,
                    password_hash=?
                '
            );

            $stmt->execute(
                [
                    Uuid::uuid7()->getBytes(),
                    $data['name'],
                    $data['email'],
                    $data['date_of_birth'],
                    password_hash( $data['password'], PASSWORD_DEFAULT ),
                ]
            );
        }

        /**
         * @param string $email
         * @return array|null
         * @throws PDOException
         */
        public static function findByEmail( string $email ) : ?array
        {
            $stmt = DB::get()->prepare(
                '
                SELECT 
                    email,
                    password_hash,
                    BIN_TO_UUID(id) as id
                FROM users
                WHERE email=?
                '
            );
            $stmt->execute( [ $email ] );

            return $stmt->fetch( PDO::FETCH_ASSOC ) ?: null;
        }

        /**
         * @param string $id
         * @return array|null
         * @throws PDOException
         */
        public static function findById( string $id ) : ?array
        {
            $stmt = DB::get()->prepare(
                '
                SELECT
                    BIN_TO_UUID(id) as id,
                    name,
                    email,
                    date_of_birth 
                FROM users 
                WHERE id=UUID_TO_BIN(?)
                '
            );
            $stmt->execute( [ $id ] );

            return $stmt->fetch( PDO::FETCH_ASSOC ) ?: null;
        }

        /**
         * @param string $id
         * @param array $data
         * @return void
         * @throws PDOException
         */
        public static function update( string $id, array $data ) : void
        {
            $stmt = DB::get()->prepare(
                '
                UPDATE users 
                SET 
                    name=?,
                    email=?,
                    date_of_birth=?
                WHERE id=UUID_TO_BIN(?)
                '
            );

            $stmt->execute(
                [
                    $data['name'],
                    $data['email'],
                    $data['date_of_birth'],
                    $id,
                ]
            );
        }
    }
