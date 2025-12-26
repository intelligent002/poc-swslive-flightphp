<?php

    namespace App\Tests\Models\Me;

    use App\Exceptions\ExceptionEmailTaken;
    use App\Exceptions\ExceptionInfra;
    use App\Models\Auth\AuthModel;
    use App\Models\Me\MeModel;
    use App\Models\Messages;
    use App\Tests\FakeDB;
    use PDOException;
    use PHPUnit\Framework\TestCase;

    class MeModelTest extends TestCase
    {
        /**
         * @throws ExceptionInfra
         * @throws ExceptionEmailTaken
         */
        public function test_validation_bad_email() : void
        {
            $result = MeModel::register(
                [
                    'name' => 'name',
                    'email' => 'bad@email',
                    'password' => 'secret-secret',
                    'password_confirm' => 'secret-secret',
                    'date_of_birth' => '2000-12-12',
                ]
            );

            $this->assertFalse( $result['ok'] );
            $this->assertEquals(
                [ 'email' => Messages::EMAIL_IS_INVALID ],
                $result['errors']
            );
        }

        /**
         * @return void
         */
        public function test_login_success() : void
        {
            FakeDB::when(
                'SELECT email, password_hash, BIN_TO_UUID(id) as id FROM users WHERE email=?',
                [
                    'id' => 'uuid-123',
                    'password_hash' => password_hash( 'secret', PASSWORD_DEFAULT )
                ]
            );

            $result = AuthModel::login(
                [
                    'email' => 'a@test.com',
                    'password' => 'secret'
                ]
            );

            $this->assertTrue( $result['ok'] );
            $this->assertEqualsCanonicalizing( 'uuid-123', $result['data']['id'] );
        }

        /**
         * @return void
         */
        public function test_login_no_such_user() : void
        {
            FakeDB::when(
                'SELECT email, password_hash, BIN_TO_UUID(id) as id FROM users WHERE email=?',
                null
            );
            $result = AuthModel::login(
                [
                    'email' => 'not@existing.email',
                    'password' => 'never-mind'
                ]
            );
            $this->assertFalse( $result['ok'] );
        }

        /**
         * @return void
         */
        public function test_login_there_is_user_but_wrong_password() : void
        {
            FakeDB::when(
                'SELECT email, password_hash, BIN_TO_UUID(id) as id FROM users WHERE email=?',
                [
                    'id' => 'uuid-123',
                    'password_hash' => password_hash( 'secret', PASSWORD_DEFAULT ),
                ]
            );

            $result = AuthModel::login(
                [
                    'email' => 'a@test.com',
                    'password' => 'wrong',
                ]
            );

            $this->assertFalse( $result['ok'] );
        }

        /**
         * @return void
         * @throws ExceptionEmailTaken
         * @throws ExceptionInfra
         */
        public function test_register_duplicate_email() : void
        {
            $e = new PDOException(
                'Duplicate entry \'a@test.com\' for key \'ux_users_email\'',
                '23000'
            );

            $e->errorInfo = [
                '23000',
                1062,
                'Duplicate entry \'a@test.com\' for key \'ux_users_email\'',
            ];

            FakeDB::whenException(
                'INSERT INTO users',
                $e
            );

            $this->expectException( ExceptionEmailTaken::class );

            MeModel::register(
                [
                    'email' => 'a@test.com',
                    'password' => 'secret-secret',
                    'password_confirm' => 'secret-secret',
                    'date_of_birth' => '2002-12-12',
                    'name' => 'Alice',
                ]
            );
        }

        /**
         * @return void
         * @throws ExceptionEmailTaken
         * @throws ExceptionInfra
         */
        public function test_register_database_error() : void
        {
            FakeDB::whenException(
            /** @lang text */
                'INSERT INTO users',
                new PDOException( 'no space left on drive' )
            );

            $this->expectException( ExceptionInfra::class );

            MeModel::register(
                [
                    'email' => 'a@test.com',
                    'password' => 'secret-secret',
                    'password_confirm' => 'secret-secret',
                    'date_of_birth' => '2002-12-12',
                    'name' => 'Alice',
                ]
            );
        }

        protected function setUp() : void
        {
            FakeDB::reset();
        }
    }
