<?php

    require __DIR__ . '/auth.php';

    use App\API\v1\Controllers\AuthController;
    use App\API\v1\Controllers\MeController;

    /* Authentication related */
    Flight::route( 'POST /v1/login', [ AuthController::class, 'login' ] );
    Flight::route( 'POST /v1/logout', [ AuthController::class, 'logout' ] );

    /* ME-CRUD */
    Flight::route( 'POST /v1/register', [ MeController::class, 'register' ] );
    Flight::route( 'GET /v1/me', [ MeController::class, 'fetch' ] );
    Flight::route( 'PUT /v1/me', [ MeController::class, 'update' ] );
