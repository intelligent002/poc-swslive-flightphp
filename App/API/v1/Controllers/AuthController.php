<?php

    namespace App\API\v1\Controllers;

    use App\Exceptions\ExceptionInfra;
    use App\Models\Auth\AuthModel;
    use App\Models\Messages;
    use Flight;

    class AuthController
    {
        /**
         * on success - will void and set the session cookie
         * on failure - will return failure object
         *
         * @return void
         */
        public static function login() : void
        {
            try {
                $result = AuthModel::login(
                    Flight::request()->data->getData()
                );

                if ( $result['ok'] ) {
                    session_regenerate_id( true );
                    $_SESSION['user_id'] = $result['data']['id'];
                    $_SESSION['email'] = $result['data']['email'];
                    unset( $result['data'] );
                }
                Flight::json( $result, $result['ok'] ? 200 : 401 );
            } catch ( ExceptionInfra $e ) {
                Flight::json( [ 'ok' => false, 'errors' => [ 'system' => Messages::GENERIC_ERROR ] ], 503 );
            }
        }

        /**
         * destroy the session and done
         *
         * @return void
         */
        public static function logout() : void
        {
            session_destroy();
            Flight::json( [ 'ok' => true ], 200 );
        }
    }
