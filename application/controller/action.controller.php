<?php

    use cogwheel\folder;
    use cogwheel\gmail;
    use cogwheel\user;
    use core\controller;

    class ControllerAction extends controller {
        function action_delete() {
            $user = new user( $_SESSION[ "user_email" ] );
            if ( $user->loggedIn() ) {
                if ( isset( $_POST[ "id" ] ) && !empty( $_POST[ "id" ] ) && isset( $_POST[ "folder" ] ) && !empty( $_POST[ "folder" ] ) ) {
                    $folder = new folder( null, $_POST[ "folder" ] );
                    $gmail = new gmail( $folder, $user->getEmail(), $user->getAppPassword() );
                    $gmail->delete( $_POST[ "id" ], $folder, true );
                }
            }
            header( "location: /" );
        }

        function action_seen() {
            $user = new user( $_SESSION[ "user_email" ] );
            if ( $user->loggedIn() ) {
                if ( isset( $_POST[ "id" ] ) && !empty( $_POST[ "id" ] ) && isset( $_POST[ "folder" ] ) && !empty( $_POST[ "folder" ] ) ) {
                    $folder = new folder( null, $_POST[ "folder" ] );
                    $gmail = new gmail( $folder, $user->getEmail(), $user->getAppPassword() );
                    $gmail->seen( $_POST[ "id" ], $folder, 1 );
                }
            }
            header( "location: /" );
        }
    }