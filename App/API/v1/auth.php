<?php

    use App\Models\Messages;

    Flight::before(
        'start', function() {
        if ( session_status() !== PHP_SESSION_ACTIVE ) {
            session_start();
        }
    }
    );

    /**
     * @return string
     */
    function requireAuth() : string
    {
        if ( empty( $_SESSION['user_id'] ) ) {
            Flight::halt(
                401,
                json_encode(
                    [
                        'ok' => false,
                        'errors' => [
                            'system' => Messages::NOT_AUTHENTICATED,
                        ],
                    ]
                )
            );
        }

        return (string) $_SESSION['user_id'];
    }
