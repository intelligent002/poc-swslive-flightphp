<?php

    namespace App\Models\Me;

    use App\DAL\MeDAL;
    use App\Exceptions\ExceptionEmailTaken;
    use App\Exceptions\ExceptionInfra;
    use App\Exceptions\ExceptionUserUnavailable;
    use App\Models\Common\CommonSanitizers;
    use App\Models\Common\CommonValidators;
    use PDOException;

    class MeModel
    {
        /**
         * @param string $uid
         * @return array
         * @throws ExceptionInfra
         * @throws PDOException
         * @throws ExceptionUserUnavailable
         */
        public static function fetch( string $uid ) : array
        {
            try {
                $user = MeDAL::findById( $uid );

                if ( !$user ) {
                    throw new ExceptionUserUnavailable();
                }
                return [
                    'ok' => true,
                    'data' => $user,
                ];

            } catch ( PDOException $e ) {
                // db issues
                throw new ExceptionInfra();
            }

        }

        /**
         * @param string $uid
         * @param array $data
         * @return array
         * @throws ExceptionEmailTaken
         * @throws ExceptionInfra
         */
        public static function update( string $uid, array $data ) : array
        {
            // update
            try {
                // sanitize (extra spaces during login may fail us)
                $data = CommonSanitizers::sanitizeAll( $data );

                // validate
                $errors = CommonValidators::comboValidateUpdate( $data );

                if ( $errors ) {
                    return [ 'ok' => false, 'errors' => $errors ];
                }

                MeDAL::update( $uid, $data );

                return [ 'ok' => true ];

            } catch ( PDOException $e ) {

                // MySQL duplicate key error
                if (
                    (string) $e->getCode() === '23000'
                    && ( $e->errorInfo[1] ?? null ) === 1062
                ) {
                    throw new ExceptionEmailTaken();
                }

                // Any other DB failures
                throw new ExceptionInfra();
            }
        }

        /**
         * @param array $data
         * @return array
         * @throws ExceptionEmailTaken
         * @throws ExceptionInfra
         */
        public static function register( array $data ) : array
        {
            // create
            try {
                // sanitize (extra spaces during login may fail us)
                $data = CommonSanitizers::sanitizeAll( $data );

                // validate
                $errors = CommonValidators::comboValidateRegister( $data );

                if ( $errors ) {
                    return [ 'ok' => false, 'errors' => $errors ];
                }

                MeDAL::create( $data );

                return [ 'ok' => true ];

            } catch ( PDOException $e ) {

                // MySQL duplicate key error
                if (
                    (string) $e->getCode() === '23000'
                    && ( $e->errorInfo[1] ?? null ) === 1062
                ) {
                    throw new ExceptionEmailTaken();
                }

                // Any other DB failures
                throw new ExceptionInfra();
            }
        }
    }
