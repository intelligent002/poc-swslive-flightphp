<?php

    namespace App\API\v1\Controllers;

    use App\Exceptions\ExceptionEmailTaken;
    use App\Exceptions\ExceptionInfra;
    use App\Exceptions\ExceptionUserUnavailable;
    use App\Models\Me\MeModel;
    use App\Models\Messages;
    use Flight;

    class MeController
    {
        /**
         * sign up to the service, no credit card required
         *
         * @return void
         */
        public static function register() : void
        {
            try {
                $result = MeModel::register(
                    Flight::request()->data->getData()
                );
                Flight::json( $result, $result['ok'] ? 200 : 422 );
            } catch ( ExceptionEmailTaken $e ) {
                Flight::json( [ 'ok' => false, 'errors' => [ 'email' => Messages::EMAIL_IS_TAKEN ] ], 409 );
            } catch ( ExceptionInfra $e ) {
                Flight::json( [ 'ok' => false, 'errors' => [ 'system' => Messages::GENERIC_ERROR ] ], 503 );
            }
        }

        /**
         * fetch my details
         *
         * @return void
         */
        public static function fetch() : void
        {
            $uid = requireAuth();
            try {
                $result = MeModel::fetch( $uid );
                unset( $result['data']['id'] );
                Flight::json( $result, 200 );
            } catch ( ExceptionUserUnavailable $e ) {
                // user was deleted mid-session - re-login
                session_destroy();
                Flight::json( [ 'ok' => false, 'errors' => [ 'system' => Messages::USER_UNAVAILABLE ] ], 401 );
            } catch ( ExceptionInfra $e ) {
                // something is odd - let the guy re-login
                session_destroy();
                Flight::json( [ 'ok' => false, 'errors' => [ 'system' => Messages::GENERIC_ERROR ] ], 503 );
            }
        }

        /**
         * update my details
         *
         * @return void
         */
        public static function update() : void
        {
            $uid = requireAuth();
            $data = Flight::request()->data->getData();
            try {
                $result = MeModel::update( $uid, $data );
                Flight::json( $result, $result['ok'] ? 200 : 422 );
            } catch ( ExceptionEmailTaken $e ) {
                Flight::json( [ 'ok' => false, 'errors' => [ 'email' => Messages::EMAIL_IS_TAKEN ] ], 409 );
            } catch ( ExceptionInfra $e ) {
                Flight::json( [ 'ok' => false, 'errors' => [ 'system' => Messages::GENERIC_ERROR ] ], 503 );
            }
        }
    }
