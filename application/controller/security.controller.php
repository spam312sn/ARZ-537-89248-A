<?php

    use cogwheel\security;
    use cogwheel\user;
    use core\controller;

    class ControllerSecurity extends controller {
        function action_validate() {
            if ( isset( $_POST[ "submit" ] ) ) {
                $fields = [
                    "gmail",
                    "password",
                    "app_password",
                ];
                $security = new security();
                $user = new user();
                foreach ( $fields as $field ) {
                    if ( isset( $_POST[ $field ] ) && !empty( $_POST[ $field ] ) ) {
                        switch ( $field ) {
                            case "gmail":
                                $user->setEmail( $security->protect( $_POST[ $field ] ) );
                                break;
                            case "password":
                                $user->setPassword( $security->secure( $security->protect( $_POST[ $field ] ) ) );
                                break;
                            case "app_password":
                                $user->setAppPassword( $security->protect( $_POST[ $field ] ) );
                                break;
                        }
                    }
                }

                if ( $user->exist() == false ) {
                    $user->setUser();
                } else {
                    $user->login();
                }
            }

            header( "location: /" );
        }

        function action_logout() {
            $error = 0;
            if ( !isset( $_SESSION[ "user_email" ] ) || empty( $_SESSION[ "user_email" ] ) ) {
                $error++;
            }
            if ( !isset( $_SESSION[ "user_hash" ] ) || empty( $_SESSION[ "user_hash" ] ) ) {
                $error++;
            }

            if ( $error == 0 ) {
                $user = new user( $_SESSION[ "user_email" ] );
                if ( $user->loggedIn() ) {
                    $user->setHash( $_SESSION[ "user_hash" ] );
                    $user->logout();
                }
            }

            header( "location: /" );
        }
    }