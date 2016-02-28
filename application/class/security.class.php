<?php

    namespace cogwheel;

    use core\core;
    use mysqli;

    class security extends core {

        public function __construct() {
            parent::__construct();
        }

        /**
         * @param string $v
         * @param int    $c
         *
         * @return string
         */
        public function secure( $v = "", $c = 10 ) {
            if ( isset( $v ) ) {
                if ( strlen( $v ) != 0 && $c != 0 ) {
                    for ( $i = 0; $i < $c; $i++ ) {
                        $v = md5( $v );
                    }
                }
            }

            return $v;
        }

        /**
         * @param int $length
         *
         * @return string
         */
        public function generateRandomString( $length = 10 ) {
            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+><|/][}{:'~`;";
            $charactersLength = strlen( $characters );
            $randomString = "";
            $max = floatval( $length * rand( 0, 256 ) ) / rand( 1, 96 );
            for ( $i = 0; $i < round( $max ); $i++ ) {
                $randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
            }

            return $randomString;
        }


        /**
         * @param string $v
         *
         * @return string
         */
        public function protect( $v = "" ) {
            if ( isset( $v ) ) {
                if ( !empty( $v ) ) {
                    $answer = mysqli_real_escape_string( $this->getDatabase()->getMysqli(), $v );

                    return $answer;
                } else {
                    die();
                }
            } else {
                die();
            }
        }

        /**
         * @param string $data
         * @param string $type
         *
         * @return bool
         */
        public function isValide( $data = "", $type = "" ) {
            $answer = false;
            $rule = [
                'email'    => '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            ];
            if ( preg_match( $rule[ $type ], $data ) ) {
                $answer = true;
            }

            return $answer;
        }
    }
