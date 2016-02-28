<?php

    use cogwheel\folder;
    use cogwheel\gmail;
    use cogwheel\user;
    use core\controller;

    class ControllerMain extends controller {
        public function action_index() {
            $this->setTemplate( "template" );
            $user = [];

            if ( !empty( $_SESSION[ "user_email" ] ) ) {
                $user = new user( $_SESSION[ "user_email" ] );
                if ( strlen( $this->checkIfEmpty( 2 ) ) == 0 ) {
                    header( "location: /u/inbox" );
                } else {
                    $gmail = new gmail( $this->checkIfEmpty( 2 ), $user->getEmail(), $user->getAppPassword() );
                    $gmail->getMessages();
                    $data = new MainModel( $gmail );
                    $page = empty( $this->checkIfEmpty( 3 ) ) ? 1 : intval( $this->checkIfEmpty( 3 ) );
                    $data->show( new folder( $this->checkIfEmpty( 2 ) ), $page );
                    $this->setData( $data->getData() );
                }
            }

            $this->setInclude( "login" );
            if ( !empty( $user ) ) {
                if ( is_object( $user ) && $user->loggedIn() == true ) {
                    $this->setInclude( "main" );
                }
            }

            $this->generate();
        }
    }