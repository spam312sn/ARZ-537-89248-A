<?php
    namespace cogwheel;

    use core\core;

    class user extends core {
        private $email;
        private $password;
        private $hash;
        private $appPassword;
        private $user;

        /**
         * @param null $email
         */
        public function __construct( $email = null ) {
            parent::__construct();
            $this->security = new security();
            if ( !is_null( $email ) ) {
                $this->setEmail( $email );
                $this->user = $this->getUser();
            }
        }

        public function loggedIn() {
            $answer = false;
            if ( isset( $_SESSION[ "user_email" ] ) && !empty( $_SESSION[ "user_email" ] ) ) {
                if ( $this->exist() == true ) {
                    if ( isset( $_SESSION[ "user_hash" ] ) && !empty( $_SESSION[ "user_hash" ] ) ) {
                        if ( strcmp( $this->getHash(), $_SESSION[ "user_hash" ] ) == 0 ) {
                            $answer = true;
                        } else {
                            $this->logout();
                        }
                    }
                }
            }

            return $answer;
        }

        public function exist() {
            $answer = false;
            $query = "SELECT COUNT(`id`) as total FROM `users` WHERE `gmail`='" . $this->getEmail() . "'";
            $count = $this->getDatabase()->getMysqli()->query( $query )->fetch_assoc();
            if ( $count[ "total" ] > 0 ) {
                $answer = true;
            } else {
                $this->logout();
            }

            return $answer;
        }

        public function getUser() {
            $answer = [ ];
            $query = "SELECT * FROM `users` WHERE `gmail`='" . $this->getEmail() . "'";
            $query = $this->getDatabase()->getMysqli()->query( $query );
            for ( $result = [ ]; ( $row = $query->fetch_assoc() ) != false; $result[] = $row ) ;
            if ( !empty( $result ) ) {
                $answer = $result[ 0 ];
            } else {
                $this->logout();
            }

            return $answer;
        }

        /**
         * @return mixed
         */
        public function getId() {
            return $this->user[ "id" ];
        }

        /**
         * @return mixed
         */
        protected function getPassword() {
            return !empty( $this->password ) ? $this->password : $this->user[ "password" ];
        }

        /**
         * @param mixed $password
         */
        public function setPassword( $password ) {
            $this->password = $password;
        }

        /**
         * @return mixed
         */
        public function getHash() {
            return !empty( $this->hash ) ? $this->hash : $this->user[ "hash" ];
        }

        /**
         * @param mixed $hash
         */
        public function setHash( $hash ) {
            $this->hash = $hash;
        }

        /**
         * @return mixed
         */
        public function getAppPassword() {
            return !empty( $this->appPassword ) ? $this->appPassword : $this->user[ "app_password" ];
        }

        /**
         * @param mixed $appPassword
         */
        public function setAppPassword( $appPassword ) {
            $this->appPassword = $appPassword;
        }

        /**
         * @return mixed
         */
        public function getEmail() {
            return !empty( $this->email ) ? $this->email : $this->user[ "gmail" ];
        }

        /**
         * @param mixed $email
         */
        public function setEmail( $email ) {
            $this->email = $email;
        }

        public function setUser() {
            $this->setHash( $this->security->secure( $this->security->generateRandomString() ) );
            if (
                !empty( $this->getEmail() ) &&
                !empty( $this->getPassword() ) &&
                !empty( $this->getAppPassword() )
            ) {
                $query = ""
                    . " INSERT INTO `users` "
                    . " (`gmail`, `password`, `app_password`, `hash`) "
                    . " VALUES "
                    . " ( "
                    . " '" . $this->security->protect( $this->getEmail() ) . "', "
                    . " '" . $this->security->protect( $this->getPassword() ) . "', "
                    . " '" . $this->security->protect( $this->getAppPassword() ) . "', "
                    . " '" . $this->security->protect( $this->getHash() ) . "' "
                    . " ); ";
                $this->getDatabase()->getMysqli()->query( $query );

                $this->login();
            }
        }

        public function login() {
            $this->setHash( $this->security->secure( $this->security->generateRandomString() ) );
            if (
                !empty( $this->getEmail() ) &&
                !empty( $this->getPassword() )
            ) {
                $query = ""
                    . " UPDATE `users` "
                    . " SET `hash` = '" . $this->security->protect( $this->getHash() ) . "' "
                    . " WHERE `gmail` = '" . $this->getEmail() . "' "
                    . " AND `password` = '" . $this->getPassword() . "' ";
                $this->getDatabase()->getMysqli()->query( $query );

                $_SESSION[ "user_email" ] = $this->getEmail();
                $_SESSION[ "user_hash" ] = $this->getHash();
            }
        }

        public function logout() {
            if (
                !empty( $this->getEmail() ) &&
                !empty( $this->getHash() )
            ) {
                $query = ""
                    . " UPDATE `users` "
                    . " SET `hash` = '' "
                    . " WHERE `gmail` = '" . $this->getEmail() . "' "
                    . " AND `hash` = '" . $this->getHash() . "' ";
                $this->getDatabase()->getMysqli()->query( $query );
            }
            $_SESSION[ "user_email" ] = null;
            $_SESSION[ "user_hash" ] = null;

            session_destroy();
        }
    }