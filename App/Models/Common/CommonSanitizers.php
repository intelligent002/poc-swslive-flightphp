<?php

    namespace App\Models\Common;

    class CommonSanitizers
    {
        /* =========================
         * Public combo function
         * ========================= */

        /**
         * @param array $data
         * @return array
         */
        public static function sanitizeAll( array $data ) : array
        {
            $result = [];

            foreach ( $data as $var => $val ) {
                switch ( $var ) {
                    case "email" :
                        {
                            $result["email"] = self::sanitizeEmail( $val );
                            break;
                        }
                    default :
                        {
                            $result[$var] = self::sanitizeGeneric( $val );
                        }
                }
            }

            return $result;
        }

        /**
         * @param string $value
         * @return string
         */
        public static function sanitizeEmail( string $value ) : string
        {
            $value = self::sanitizeGeneric( $value );
            return strtolower( $value );
        }

        /**
         * @param string $value
         * @return string
         */
        public static function sanitizeGeneric( string $value ) : string
        {
            return trim( $value );
        }
    }
