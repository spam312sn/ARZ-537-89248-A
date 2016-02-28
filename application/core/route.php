<?php

    namespace core;

    class route {
        static function start() {
            $controllerClass = new controller();

            /** @var mixed $routes */
            $routes = $controllerClass->getRoute();
            $allowedDepth = 4;

            if ( count( $routes ) > $allowedDepth ) {
                $path = '';
                for ( $i = 1; $i <= $allowedDepth; $i++ ) {
                    $path = !empty( $routes[ $i ] ) ? $path . '/' . $routes[ $i ] : $path . '';

                }
                header( "location: " . $path );
            }

            if ( !isset( $routes[ 1 ] ) || empty( $routes[ 1 ] ) || ( strcmp( $routes[ 1 ], "u" ) == 0 ) ) {
                $controllerName = 'main';
            } else {
                $controllerName = $routes[ 1 ];
            }
            $actionName = isset( $routes[ 2 ] ) && !empty( $routes[ 2 ] ) ? $routes[ 2 ] : 'index';

            $modelName = $controllerName . '.model';
            $modelFile = strtolower( $modelName ) . '.php';
            $modelPath = 'application/model/' . $modelFile;
            if ( file_exists( $modelPath ) ) {
                include_once 'application/model/' . $modelFile;
            }

            $controllerClass = "Controller" . ucwords( $controllerName );
            $controllerName = $controllerName . '.controller';
            $controllerFile = strtolower( $controllerName ) . '.php';
            $controllerPath = "application/controller/" . $controllerFile;
            if ( file_exists( $controllerPath ) ) {
                include_once 'application/controller/' . $controllerFile;
            } else {
                Route::ErrorPage404();
            }

            $controller = new $controllerClass;

            $action = 'action_' . $actionName;
            if ( strcmp( $routes[ 1 ], "u" ) == 0 ) {
                $action = 'action_index';
            }

            if ( method_exists( $controller, $action ) ) {
                $controller->$action();
            } else {
                Route::ErrorPage404();
            }
        }

        function ErrorPage404() {
            $host = 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/';
            header( 'HTTP/1.1 404 Not Found' );
            header( "Status: 404 Not Found" );
            header( 'Location:' . $host . '404' );
        }

    }
