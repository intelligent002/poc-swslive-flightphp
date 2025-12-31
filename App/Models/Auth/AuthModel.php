<?php

    namespace App\Models\Auth;

    use App\DAL\MeDAL;
    use App\Exceptions\ExceptionInfra;
    use App\Models\Common\CommonSanitizers;
    use App\Models\Messages;
    use PDOException;

    class AuthModel
    {

        /**
         * @param array $data
         * @return array
         * @throws ExceptionInfra
         */
        public static function login( array $data ) : array
        {
            try {
                // sanitize (extra spaces during login may fail us)
                $data = CommonSanitizers::sanitizeAll( $data );

                // seek for the user
                $user = MeDAL::findByEmail( $data['email'] );

                // validate whatever found
                if (
                    !$user ||
                    !password_verify( $data['password'], $user['password_hash'] )
                ) {
                    // return pessimistic
                    return [
                        'ok' => false,
                        'errors' => [ 'credentials' => Messages::INVALID_CREDENTIALS ]
                    ];
                }

                // return optimistic
                return [ 'ok' => true, 'data' => $user ];
            } catch ( PDOException $e ) {
                throw new ExceptionInfra();
            }
        }
    }
