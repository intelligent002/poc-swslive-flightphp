<?php

    namespace App\API\v1\Responses;

    use App\Exceptions\ExceptionEmailTaken;
    use App\Exceptions\ExceptionInfra;
    use App\Exceptions\ExceptionUserUnauthenticated;
    use App\Exceptions\ExceptionUserUnavailable;
    use App\Models\Messages;
    use Flight;
    use Throwable;

    class ExceptionHandler
    {
        public static function handle( Throwable $e ) : void
        {
            switch ( true ) {

                case $e instanceof ExceptionEmailTaken:
                    {
                        Flight::jsonHalt(
                            Response::error( [ 'email' => Messages::EMAIL_IS_TAKEN ] ),
                            409
                        );
                        break;
                    }

                case $e instanceof ExceptionUserUnavailable:
                    {
                        session_destroy();
                        Flight::jsonHalt(
                            Response::error( [ 'system' => Messages::USER_UNAVAILABLE ] ),
                            401
                        );
                        break;
                    }

                case $e instanceof ExceptionUserUnauthenticated:
                    {
                        session_destroy();
                        Flight::jsonHalt(
                            Response::error( [ 'system' => Messages::NOT_AUTHENTICATED ] ),
                            401
                        );
                        break;
                    }
                case $e instanceof ExceptionInfra:
                    {
                        Flight::jsonHalt(
                            Response::error( [ 'system' => Messages::GENERIC_ERROR ] ),
                            503
                        );
                        break;
                    }
                default:
                    // specifically left unhandled, we want it to crash loudly during deployment other issues
            }
        }
    }
