<?php

    use cogwheel\log;
    use core\controller;

    class ControllerInstall extends controller {
        function action_index() {
            $log = new log();

            $sqlFolder = '/var/www/ARZ-537-89248/sql/';
            $tables = [ "folders", "letters", "users" ];
            $fileType = "sql";

            foreach ( $tables as $table ) {
                $file = $sqlFolder . $table . "." . $fileType;
                $log->eco( "Prepare table '" . $table . "'" );
                $fileExist = file_exists( $file );
                $log->eco( "Check if exist file '" . $file . "'", $fileExist );
                $fOpen = fopen( $file, "r" );
                $log->eco( "Open File", $fOpen );
                $content = fread( $fOpen, filesize( $file ) );
                $log->eco( "Get content", empty( $content ) ? false : true );
                $parts = explode( ";", $content );
                $i = 0;
                foreach ( $parts as $part ) {
                    if ( strlen( $part ) > 1 ) {
                        $i++;
                        switch ( $i ) {
                            case 1:
                                $status = "Dropping the table";
                                break;
                            case 2:
                                $status = "Creating the table";
                                break;
                            case 3:
                                $status = "Set Default data";
                                break;
                            default:
                                $status = "";
                                break;
                        }
                        $mysqli = $this->connect();
                        $log->eco( $status, $mysqli->query( $part ) );
                        $mysqli->close();
                    }
                }
            }

            print "<tt><b>>&nbsp;<a href='/'>Go to home directory</a></tt></b>";
        }
    }