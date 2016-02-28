<?php
    namespace cogwheel;

    use mysqli;

    class database {

        /** @var mysqli|boolean $mysqli  */
        private $mysqli;

        /**
         * @param $host
         * @param $user
         * @param $password
         * @param $database
         * @param $port
         * @param $debug
         */
        public function __construct( $host, $user, $password, $database, $port, $debug ) {
            $mysqli = new mysqli;
            $mysqli->connect( $host, $user, $password, $database );
            if ( !$mysqli->connect_error ) {
                $this->setMysqli( $mysqli );
            } else {
                $this->setMysqli( false );
                if ( $debug ) {
                    die( "Mysql: ".$mysqli->connect_error );
                } else {
                    die();
                }
            }
        }

        /**
         * @return mysqli
         */
        public function getMysqli() {
            return $this->mysqli;
        }

        /**
         * @param mysqli $mysqli
         */
        public function setMysqli( $mysqli ) {
            $this->mysqli = $mysqli;
        }

        /**
         * @return bool
         */
        public function test() {
            $answer = true;
            if ( $this->getMysqli() != false ) {
                /** @var mysqli $mysqli */
                $mysqli = $this->getMysqli();
                $query = ""
                    . " SELECT * "
                    . " FROM `users` "
                    . " WHERE 1 ";
                $result = $mysqli->query( $query );
                if ( $result == false ) {
                    $answer = false;
                }
            }

            return $answer;
        }
    }