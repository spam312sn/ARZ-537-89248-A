<?php
    namespace core;

    use cogwheel\database;
    use mysqli;

    class core {
        private $route;
        private $database;

        public function __construct() {
            $this->route = explode( '/', $_SERVER[ 'REQUEST_URI' ] );
            $this->setDatabase();
            if ( strcmp( $this->checkIfEmpty( 1 ), 'install' ) != 0 ) {
                if ( $this->getDatabase()->test() == false ) {
                    header( "location: /install" );
                }
            }
        }

        /**
         * @param int $fn
         *
         * @return string
         */
        public function checkIfEmpty( $fn = 0 ) {
            $route = $this->getRoute();
            $answer = '';
            if ( isset( $route[ $fn ] ) ) {
                if ( !empty( $route[ $fn ] ) ) {
                    $answer = $route[ $fn ];
                }
            }

            return $answer;
        }

        /**
         * @return array
         */
        public function getRoute() {
            return $this->route;
        }

        /**
         * @return mysqli
         */
        public function connect() {
            $this->setDatabase();
            return $this->getDatabase()->getMysqli();
        }

        /**
         * @return database
         */
        public function getDatabase() {
            return $this->database;
        }

        public function setDatabase() {
            $this->database = new database( host, user, pswd, dtbs, port, debug );
        }
    }