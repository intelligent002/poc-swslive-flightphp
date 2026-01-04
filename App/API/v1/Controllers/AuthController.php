<?php

    namespace App\API\v1\Controllers;

    use App\API\v1\Responses\Response;
    use App\Exceptions\ExceptionInfra;
    use App\Models\Auth\AuthModel;
    use Flight;

    class AuthController
    {
        /**
         * on success - will void and set the session cookie
         * on failure - will return failure object
         *
         * @return void
         * @throws ExceptionInfra
         */
        public static function login() : void
        {

            $result = AuthModel::login(
                Flight::request()->data->getData()
            );

            if ( $result['ok'] ) {
                session_regenerate_id( true );
                $_SESSION['user_id'] = $result['data']['id'];
                Flight::json( Response::OK(), 200 );
            } else {
                Flight::json( Response::ERROR( $result['errors'] ), 401 );
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
            Flight::json( Response::OK(), 200 );
        }
    }
