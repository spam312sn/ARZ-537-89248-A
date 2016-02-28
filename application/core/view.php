<?php

    namespace core;

    /**
     * Class view
     * @package core
     */
    class view {

        /**
         * @param string $contentView
         * @param string $templateView
         * @param mixed  $data
         */
        public function generate( $templateView = "", $data = [ ], $contentView = "" ) {
            if ( strlen( $templateView ) == 0 ) {
                $templateView = "template";
            }
            $templateView .= '.view.php';
            $contentView .= '.view.php';
            require_once "application/view/" . strval( $templateView );
        }
    }
