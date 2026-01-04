<?php

    namespace App\API\v1\Controllers;

    use App\API\v1\Responses\Response;
    use App\Exceptions\ExceptionEmailTaken;
    use App\Exceptions\ExceptionInfra;
    use App\Exceptions\ExceptionUserUnauthenticated;
    use App\Exceptions\ExceptionUserUnavailable;
    use App\Models\Me\MeModel;
    use Flight;

    class MeController
    {
        /**
         * sign up to the service, no credit card required
         *
         * @return void
         * @throws ExceptionEmailTaken
         * @throws ExceptionInfra
         */
        public static function register() : void
        {
            $result = MeModel::register(
                Flight::request()->data->getData()
            );

            if ( $result['ok'] ) {
                Flight::json( Response::OK(), 200 );
            } else {
                Flight::json( Response::ERROR( $result['errors'] ), 422 );
            }
        }

        /**
         * fetch my details
         *
         * @return void
         * @throws ExceptionInfra
         * @throws ExceptionUserUnavailable
         * @throws ExceptionUserUnauthenticated
         */
        public static function fetch() : void
        {
            $uid = requireAuth();

            $result = MeModel::fetch( $uid );
            Flight::json(
                Response::OK(
                    [
                        // opt-in required fields
                        "name" => $result['data']['name'],
                        "email" => $result['data']['email'],
                        "date_of_birth" => $result['data']['date_of_birth']
                    ]
                ), 200
            );
        }

        /**
         * update my details
         *
         * @return void
         * @throws ExceptionEmailTaken
         * @throws ExceptionInfra
         * @throws ExceptionUserUnauthenticated
         */
        public static function update() : void
        {
            $uid = requireAuth();
            $data = Flight::request()->data->getData();

            $result = MeModel::update( $uid, $data );
            if ( $result['ok'] ) {
                Flight::json( Response::OK(), 200 );
            } else {
                Flight::json( Response::ERROR( $result['errors'] ), 422 );
            }
        }
    }
