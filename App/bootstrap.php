<?php

    use App\API\v1\Responses\ExceptionHandler;
    use App\Infra\DB;
    use App\Infra\PdoConnection;

    require __DIR__ . '/../vendor/autoload.php';

    Flight::map(
        'error',
        function( Throwable $e ) {
            ExceptionHandler::handle( $e );
        }
    );

    $db_host = getenv( 'DB_HOST' );
    $db_name = getenv( 'DB_NAME' );
    $db_user = getenv( 'DB_USER' );
    $db_pass = getenv( 'DB_PASS' );

    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    DB::set( new PdoConnection( $pdo ) );