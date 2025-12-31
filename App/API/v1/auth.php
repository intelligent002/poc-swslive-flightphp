<?php

    use App\Exceptions\ExceptionUserUnauthenticated;

    Flight::before(
        'start', function() {
        if ( session_status() !== PHP_SESSION_ACTIVE ) {
            session_start();
        }
    }
    );

    /**
     * @return string
     * @throws ExceptionUserUnauthenticated
     */
    function requireAuth() : string
    {
        if ( empty( $_SESSION['user_id'] ) ) {
            throw new ExceptionUserUnauthenticated();
        }

        return (string) $_SESSION['user_id'];
    }
