<?php

    namespace App\Models\Common;

    use App\Models\Messages;
    use DateTime;
    use DateTimeImmutable;
    use Exception;

    class CommonValidators
    {
        /**
         * @param array $data
         * @return array
         */
        public static function comboValidateRegister( array $data ) : array
        {
            $errors = [];

            $errors += self::validateName( $data );
            $errors += self::validateEmail( $data );
            $errors += self::validateDateOfBirth( $data );
            $errors += self::validatePassword( $data );

            return $errors;
        }

        /**
         * @param array $data
         * @return array
         */
        private static function validateName( array $data ) : array
        {
            if ( empty( $data['name'] ) ) {
                return [ 'name' => Messages::NAME_IS_REQUIRED ];
            }

            if ( strlen( $data['name'] ) > 120 ) {
                return [ 'name' => Messages::NAME_IS_TOO_LONG ];
            }

            return [];
        }

        /**
         * @param array $data
         * @return array
         */
        private static function validateEmail( array $data ) : array
        {
            if ( empty( $data['email'] ) ) {
                return [ 'email' => Messages::EMAIL_IS_REQUIRED ];
            }

            if ( strlen( $data['email'] ) > 120 ) {
                return [ 'email' => Messages::EMAIL_IS_TOO_LONG ];
            }

            if ( !filter_var( $data['email'], FILTER_VALIDATE_EMAIL ) ) {
                return [ 'email' => Messages::EMAIL_IS_INVALID ];
            }

            return [];
        }

        /**
         * @param array $data
         * @return array
         */
        private static function validateDateOfBirth( array $data ) : array
        {
            if ( empty( $data['date_of_birth'] ) ) {
                return [ 'date_of_birth' => Messages::DOB_IS_REQUIRED ];
            }

            if ( !self::isValidDate( $data['date_of_birth'] ) ) {
                return [ 'date_of_birth' => Messages::DOB_IS_INVALID ];
            }

            if ( !self::isAdult( $data['date_of_birth'] ) ) {
                return [ 'date_of_birth' => Messages::DOB_IS_TOO_YOUNG ];
            }

            return [];
        }

        /**
         * @param string $date
         * @return bool
         */
        private static function isValidDate( string $date ) : bool
        {
            try {
                $dt = DateTime::createFromFormat( 'Y-m-d', $date );
            } catch ( Exception ) {
                return false;
            }
            return $dt !== false && $dt->format( 'Y-m-d' ) === $date;
        }

        /**
         * @param string $dob
         * @return bool
         */
        private static function isAdult( string $dob ) : bool
        {
            try {
                $birth = new DateTimeImmutable( $dob );
                $today = new DateTimeImmutable( 'today' );
            } catch ( Exception ) {
                return false;
            }

            if ( $birth > $today ) {
                return false;
            }

            return $birth->diff( $today )->y >= 18;
        }

        /**
         * @param array $data
         * @return array
         */
        private static function validatePassword( array $data ) : array
        {
            if ( empty( $data['password'] ) ) {
                return [ 'password' => Messages::PASSWORD_IS_REQUIRED ];
            }

            if ( strlen( $data['password'] ) < 8 ) {
                return [ 'password' => Messages::PASSWORD_IS_INVALID ];
            }

            if ( $data['password'] !== $data['password_confirm'] ) {
                return [ 'password' => Messages::PASSWORD_IS_MISS_MATCH ];
            }

            return [];
        }

        /**
         * @param array $data
         * @return array
         */
        public static function comboValidateUpdate( array $data ) : array
        {
            $errors = [];

            $errors += self::validateName( $data );
            $errors += self::validateEmail( $data );
            $errors += self::validateDateOfBirth( $data );

            return $errors;
        }
    }
